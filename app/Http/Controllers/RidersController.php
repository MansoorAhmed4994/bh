<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Riders;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class RidersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
