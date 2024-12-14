<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class StaffManageController extends Controller
{
    public function staffList()
    {
        if (\request()->ajax()){
            $data = User::with('department','role')
                ->where('users.user_type', 1);

            return DataTables::of($data)
                ->addColumn('department', function ($data){
                    return optional($data->department)->title;
                })->addColumn('role', function ($data){
                    return optional($data->role)->title;
                })->addColumn('status', function ($data){
                    if ($data->status === 1) {
                        $statusValue = '<span class="badge badge-success">'.__('theme.active').'</span>';
                    } else {
                        $statusValue = '<span class="badge badge-danger">'.__('theme.inactive').'</span>';
                    }
                    return $statusValue;
                })
                ->addColumn('action', function ($data){
                    $statusRoute = route('staff-status.action',$data->id);
                    $editRoute = route('staff-edit.staffEdit', $data->id);
                    if($data->status == '1') {
                        $status = '<form action="' . $statusRoute . '" method="POST">
                                                <input type="hidden" name="_token" value="' . csrf_token() .'">
                                                <input type="hidden" name="status" value="0">
                                                <button type="submit" class="badge bg-red pointer">' . __("theme.inactive") . '</button>
                                            </form>';
                    }else {
                        $status = '<form action="' . $statusRoute . '" method="POST">
                                                <input type="hidden" name="_token" value="' . csrf_token() .'">
                                                <input type="hidden" name="status" value="1">
                                                <button type="submit" class="badge bg-red pointer">' . __("theme.active") . '</button>
                                            </form>';
                    }
                    return '<a href="'.$editRoute.'" class="badge bg-primary text-white" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>'.$status;
                })
                ->rawColumns(['status','action'])->make(true);
        }
        return view('staff.index');
    }

    public function staffEdit($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        $roles = Role::all();

        return view('staff.edit', compact('user', 'departments','roles'));
    }

    public function createStaff()
    {
        $departments = Department::all();
        $roles = Role::all();
        return view('staff.create', compact('departments','roles'));
    }

    public function saveStaff(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'department' => 'required',
            'role' => 'required',
            'status' => 'required',
        ]);

        $name = $request->name;
        $email = $request->email;
        $role = $request->role;
        $department = $request->department;
        $status = $request->status;

        $saved = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($request->password),
                    'user_type' => 1,
                    'role_id' => $role,
                    'department_id' => $department,
                    'status' => $status,
                ]);

        if ($saved) {
            $notify = storeNotify('Staff');
        }else{
            $notify = errorNotify('Staff add');
        }

        return redirect()->back()->with($notify);
    }

    public function staffUpdate(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'department' => 'required',
            'status' => 'required',
        ]);

        $name = $request->name;
        $email = $request->email;
        $department = $request->department;
        $status = $request->status;
        $role = $request->role;

        $user = User::find($id);

        if ($user->email == $email){
            $user->name = $name;
            $user->department_id = $department;
            $user->status = $status;
            $user->role_id = $role;
        } else {
            $user->name = $name;
            $user->email = $email;
            $user->department_id = $department;
            $user->status = $status;
            $user->role_id = $role;
        }

        if ($user->save()) {
            $notify = updateNotify('Staff info');
        }else{
            $notify = errorNotify('Staff info update');
        }

        return redirect()->back()->with($notify);
    }

    public function action(Request $request,$id)
    {
        $status = $request->status;

        $done = User::where('id', $id)->update(['status' => $status]);

        if ($done) {
            $notify = updateNotify('Staff status');
        }else{
            $notify = errorNotify('Staff status update');
        }

        return redirect()->back()->with($notify);
    }
}
