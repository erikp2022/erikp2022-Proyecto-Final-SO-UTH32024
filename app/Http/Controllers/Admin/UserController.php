<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function userList()
    {
        if (\request()->ajax()){
            $data = User::with('tickets')->where(['is_admin' => 0 , 'user_type' => 0]);

            return DataTables::of($data)
                ->addColumn('tickets', function ($data) {
                    return $data->tickets->count();
                })
                ->addColumn('action', function ($data){
                    $statusRoute = route('staff-status.action',$data->id);
                    $editRoute = route('userEdit',$data->id);
                    if($data->status == '1') {
                        $status = '<form action="' . $statusRoute . '" method="POST">
                                                <input type="hidden" name="_token" value="' . csrf_token() .'">
                                                <input type="hidden" name="status" value="0">
                                                <button type="submit" class="badge bg-red pointer border-0 mb-1">'.__("theme.block").'</button>
                                            </form>';
                    }else {
                        $status = '<form action="' . $statusRoute . '" method="POST">
                                                <input type="hidden" name="_token" value="' . csrf_token() .'">
                                                <input type="hidden" name="status" value="1">
                                                <button type="submit" class="badge bg-red pointer border-0 mb-1">' . __("theme.unblock") . '</button>
                                            </form>';
                    }
                    return '<a href="'.$editRoute.'" class="badge bg-primary text-white border-0 mb-1" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>'.$status;
                })
                ->rawColumns(['status','action'])->make(true);
        }

        return view('users.index');
    }

    public function createUser()
    {
        return view('users.create');
    }

    public function saveUser(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'status' => 'required',
        ]);

        $saved = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);

        if ($saved) {
            $notify = storeNotify('User');
        }else{
            $notify = errorNotify('User add');
        }

        return redirect()->back()->with($notify);
    }

    public function userEdit($id)
    {
        $user = User::find($id);

        return view('users.edit', compact('user'));
    }

    public function userUpdate(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'status' => 'required',
        ]);

        $name = $request->name;
        $email = $request->email;
        $password = $request->email;
        $status = $request->status;

        $user = User::find($id);

        if ($user->email == $email){
            $user->name = $name;
            $user->password = Hash::make($password);
            $user->status = $status;
        } else {
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->status = $status;
        }

        if ($user->save()) {
            $notify = updateNotify('User info');
        }else{
            $notify = errorNotify('User info update');
        }

        return redirect()->back()->with($notify);
    }
}
