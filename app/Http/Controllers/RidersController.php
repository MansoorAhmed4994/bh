<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Riders;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Client\ManualOrders;

class RidersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $redirectTo = '/riders/dashboard';

    public function showLoginForm()
    {
        //dd();
        return view('riders.login');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    public function username()
    {
        return 'email';
    }

    public function login(Request $request)
    { 
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        // if (method_exists($this, 'hasTooManyLoginAttempts') &&
        //     $this->hasTooManyLoginAttempts($request)) {
        //     $this->fireLockoutEvent($request);

        //     return $this->sendLockoutResponse($request);
        // } 
        
        // if ($this->attemptLogin($request)) {
        //     return $this->sendLoginResponse($request);
        // }

        // // If the login attempt was unsuccessful we will increment the number of attempts
        // // to login and redirect the user back to the login form. Of course, when this
        // // user surpasses their maximum number of attempts they will get locked out.
        // $this->incrementLoginAttempts($request);
        //dd($request->email);
        if(Auth::guard('rider')->attempt($request->only('email','password'),$request->filled('remember'))){
        //Authentication passed...
            return redirect()
            ->route('riders.dashboard')
            ->with('status','You are Logged in as Rider!');
        }
        else
            {
                return redirect()->route('riders.login')->with('flash_message_error','Wrong Credientials');
            }
         
        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout(); 

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/admin/login');
    }

    public function dashboard()
    { 
        
        $lists = ManualOrders::all();

        return view ('riders.dashboard',compact('lists'));
    }

    public function index()
    {
        return view('riders.create');
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('riders.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd();
        $remeber_token = Str::random(10);
        //dd($remeber_token);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->number,
            'address' => $request->address,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'remember_token' => $request->_token,
        ]);
        
        $userRole = Role::where('name','user')->first();
        $user->roles()->attach($userRole);
        
        //dd($data->id);
        
        $riders = new Riders();
        $riders->first_name = $request->first_name;
        $riders->last_name = $request->last_name;
        $riders->address = $request->address;
        $riders->cnic = $request->cnic;
        $riders->number = $request->number;
        $riders->email = $request->email;
        $riders->whatsapp_number = $request->whatsapp_number;
        $riders->users_id = $user->id;

        $riders->created_by = Auth::id();
        $riders->updated_by = Auth::id();
        $riders->status = 'active'; 
        $riders->save(); 
        
        if($riders->save())
        {
            return redirect()->route('Riders.create')->with('success', 'Successfully Register');
            //return 'Order Successfully Placed';
        }
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
