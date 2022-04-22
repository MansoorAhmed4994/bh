<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riders;
use App\Models\Role;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Client\ManualOrders;
use DB;
use App\Models\Client\Customers;

class RidersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use RedirectsUsers, ThrottlesLogins;

    protected $redirectTo = '/riders/dashboard';

    public function showLoginForm()
    {
        //dd();
        
        return view('riders.login');
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
            ->with('status','You are Logged in as Admin!');
        }
        else
            {
                return redirect()->route('riders.login')->with('flash_message_error','Wrong Credientials');
            }
         
        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : redirect()->intended($this->redirectPath());
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
            : redirect('/riders/login');
    }

    protected function guard()
    {
        return Auth::guard('rider');
    }

    public function dashboard()
    { 
        
        $lists = ManualOrders::all();

        return view ('riders.dashboard',compact('lists'));

        //dd('working');
        $list =DB::table('manual_orders')
        ->groupBy('status')
        ->select('status', DB::raw('count(*) as total'), DB::raw('sum(price) as amount'))
        ->get();
        
        
        
        // dd($list);
        //if ($result->count()) { }
        return view('riders.dashboard')->with('lists',$list);
    }

    public function list()
    {
        // $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'manual_orders.customers_id')->where('manual_orders.status','pending')->orderBy('manual_orders.created_at', 'DESC')->paginate(5);

        

        $list = Riders::rightJoin('manual_orders', 'manual_orders.riders_id', '=', 'riders.id')
        ->where('riders.users_id',Auth::user()->id)
        ->orderBy('manual_orders.id', 'ASC')
        ->select('manual_orders.id','manual_orders.customers_id','manual_orders.receiver_number','manual_orders.description','manual_orders.reciever_address','manual_orders.price','manual_orders.images','manual_orders.total_pieces','manual_orders.date_order_paid','manual_orders.status','manual_orders.created_at','manual_orders.updated_at')
        ->paginate(20);
        //dd($list);
        return view('riders.list')->with('list',$list);
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
        
        $userRole = Role::where('name','rider')->first();
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
            return redirect()->route('riders.create')->with('success', 'Successfully Register');
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
