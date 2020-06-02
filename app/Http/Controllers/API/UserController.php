<?php

/**
 * @Author: @vedatbozkurt
 * @Email: info@wedat.org
 * @Date:   2020-04-05 17:54:27
 * @Last Modified by:   @vedatbozkurt
 * @Last Modified time: 2020-04-17 01:19:48
 */
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','show']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
       // $task = Company::join('tasks', 'tasks.user_id', '=', 'companies.id')
       // ->select('companies.name','tasks.*',)
       // ->get();
        $roles = Role::all();
        $user = User::latest()->paginate(30);
        return response()->json(array('user'=>$user,'roles'=>$roles));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            // 'user_id' => 'required',
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'status' => 'required',
            'role' => 'required'
        ]);

        $form_data = array(
            // 'user_id'       =>   $request->user_id,
            'name'        =>   $request->name,
            'email'        =>   $request->email,
            'status'        =>   $request->status,
            'role'        =>   $request->role,
            'password'        =>   Hash::make($request->password),
        );

        $user = User::create($form_data);
        $user->assignRole($request->input('role'));
        activity()->withProperties(['action' => 'create', 'status' => 'success', 'user' => auth()->user()->name])->log('created user: '.$user->name);

        return response()->json('Successfully Added');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'user_id' => 'required',
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$request->id,
            'status' => 'required',
            'role' => 'required',
            'password' => 'sometimes|required',
        ]);


        $form_data = array(
            'name'        =>   $request->name,
            'email'        =>   $request->email,
            'status'        =>   $request->status,
            'role'        =>   $request->role,
        );
        if(!empty($request->password)){
            $form_data = array(
                'password'        =>   Hash::make($request->password),
            );
        }

        $user = User::find($id);
        $user->update($form_data);
        $user->syncRoles($request->input('role'));
        activity()->withProperties(['action' => 'update', 'status' => 'success', 'user' => auth()->user()->name])->log('updated user: '.$user->name);

        return response()->json($request->role);
    }

    public function destroy($id)
    {
        $data = User::findOrFail($id);
        activity()->withProperties(['action' => 'delete', 'status' => 'success', 'user' => auth()->user()->name])->log('deleted user: '.$data->name);
        $data->delete();
        return response()->json('successfully deleted');
    }

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();
        $this->validate($request, [
            // 'user_id' => 'required',
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required',
        ]);

         if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());
        activity()->withProperties(['action' => 'update', 'status' => 'success', 'user' => auth()->user()->name])->log('updated profile');

        return response()->json('Success');
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
}
