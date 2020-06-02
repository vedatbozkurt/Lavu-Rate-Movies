<?php

/**
 * @Author: @vedatbozkurt
 * @Email: info@wedat.org
 * @Date:   2020-04-10 22:19:13
 * @Last Modified by:   @vedatbozkurt
 * @Last Modified time: 2020-04-17 01:06:32
 */
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $permission = Permission::orderBy('id', 'desc')->paginate(25);
        return response()->json($permission);
    }


    public function store(Request $request)
    {
/*        $this->validate($request, [
            'name' => 'required',
            'symbol' => 'required',
        ]);
*/
        $this->validate($request, [
            'name' => 'required',
        ]);

        $form_data = array(
            'name'       =>   $request->name,
            'guard_name'        =>   'wedat',
        );

        Permission::create($form_data);
      return response()->json('successfully added');



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
      $data = Permission::findOrFail($id);
      return response()->json($data);
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

       $this->validate($request, [
            'name' => 'required',
        ]);

        $form_data = array(
            'name'       =>   $request->name,
        );

      Permission::whereId($id)->update($form_data);
      return response()->json('successfully updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $permission = Permission::find($id);
      $permission->delete();
      return response()->json('successfully deleted');
    }
}
