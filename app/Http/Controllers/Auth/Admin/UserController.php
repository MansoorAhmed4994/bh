<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Page;
use App\Models\PageUser; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use Gate;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function __construct()
    {
        //dd();
        $this->middleware('auth:admin');
    }
     
    public function index()
    {
        //dd();
        $list = User::all(); 
        //return view('auth.admin.dashboard')->with('users',$list);
        return view('auth.admin.users.list')->with(['users'=>$list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
    public function edit( User $user)
    {
        // dd($user);
        
        $roles = Role::all();
        $pages = Page::all();
        // dd(decrypt($user->password));
        // $editusers = User::find($user);
        //return view('auth.admin.dashboard')->with('users',$list);
        return view('auth.admin.users.edit')->with([
            'user' => $user,
            'roles' => $roles,
            'pages' => $pages,
            
        ]);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $current_timestamp = Carbon::now()->toDateTimeString();
        $page_user=[];
        foreach($request->page_ids as $page)
        {
            
            $createpage='creates'.$page;
            $cs = 'no';
            $editpage='edits'.$page;
            $es = 'no';
            $deletepage='deletes'.$page;
            $ds = 'no';
            $viewpage='views'.$page;
            $vs = 'no';
            if(isset($request->$createpage))
            {
                $cs = 'yes'; 
            }
            if(isset($request->$editpage))
            {
                $es = 'yes'; 
            }
            if(isset($request->$deletepage))
            {
                $ds = 'yes'; 
            }
            if(isset($request->$viewpage))
            {
                $vs = 'yes'; 
            }
            $page_user[$page] = [
                'create' => $cs,
                'edit' => $es,
                'view' => $vs,
                'delete' => $ds,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'status' => 'active',
                ];  
        }
        // dd($page_user);
        $status = $user->page_permission()->sync($page_user, false);

        // dd($status);
        $user->roles()->sync($request->roles); 
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->email = $request->email;
        if($request->pasword_checkbox == 'pasword_checkbox')
        { 
            $user->password = Hash::make($request->password); 
        }
        $user->remember_token = $request->_token;
        $user->save();


        return redirect()->route('admin.user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // dd($user);
        //
        if(Gate::denies('delete-users')){
            return redirect()->route('admin.user.index');
        }

        $user->roles()->detach();
        $user->page_permission()->detach();
        $user->delete();

        return redirect()->route('admin.user.index');
    }
    
    public function update_page_permissions($user_id ,$page_id, $permission_type)
    {
        $page_permission = PageUser::where(['user_id'=>$user_id])->first();
        dd($page_permission);
        
             
        
        return response()->json(['messege' => 'successfully deleted']); 
        // echo 'working';
    }
    
}
