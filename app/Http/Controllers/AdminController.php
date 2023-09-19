<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function admindashboard()
    {
        return view('admin.index');
    }

    public function adminlogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function adminprofile()
    {

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_profile_view', compact('profileData'));
    }

    public function adminprofilestore(Request $request)
    {

        $id = Auth::user()->id;
        $data = User::find($id);
        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $data['photo'] = $filename;
        }

        $data->save();

        return redirect()->back();

    }

    public function adminchangepassword()
    {

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_change_password', compact('profileData'));
    }

    public function adminupdatepassword(Request $request)
    {

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        ///Match Password

        if (!Hash::check($request->old_password, auth::user()->password)) {

            $notification = array(
                'message' => 'Old Password Does not Match',
                'alert-type' => 'error',
            );
            return back()->with($notification);
        }

        ///Update new password

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        $notification = array(
            'message' => 'Password Changed Successfully',
            'alert-type' => 'success',
        );
        return back()->with($notification);
    }

    public function adminuserlist()
    {

        // $users = User::all(); /// for all role

        $users = User::where('role', 'user')->get();   /// for specific role

        return view('admin.user_list', compact('users'));
    }


    ///////////////// User Status Change /////////////////

    public function userstatus($id)
    {
        $user = User::find($id);

        if ($user) {
            if ($user->status === 'active') {
                $user->status = 'inactive';
            } else {
                $user->status = 'active';
            }
            $user->save();
        }

        $notification = array(
            'message' => 'Status Changed Successfully',
            'alert-type' => 'success',
        );
        return redirect()->back()->with($notification);

    
    }

    ////////////////////// USER Password Reset //////////////

    public function userpasswordreset($id)
    {


        $profileData = User::find($id);

        return view('admin.user_password_reset', compact('profileData'));

    }

    public function userpasswordupdate(Request $request, $id)
    {

        $user = User::find($id);
        if ($request->password) {
            $request->validate([

                'password' => 'required|confirmed',
            ]);
            $user->password = $request->password;
        }
        $user->save();

        // $request->validate([
        //     'old_password' => 'required',
        //     'new_password' => 'required|confirmed'
        // ]);

        ///Match Password

        // if(!Hash::check($request->old_password, User::where('id', $id)->get()->password)){

        //     $notification  = array(
        //         'message' => 'Old Password Does not Match',
        //         'alert-type' => 'error'
        //     );
        //     return back()->with($notification);
        // }

        ///Update new password

        // User::whereId($users()->id)->update([
        //     'password' => Hash::make($request->new_password)
        // ]);

        $notification = array(
            'message' => 'Password Changed Successfully',
            'alert-type' => 'success',
        );
        return back()->with($notification);
    }


    //////////////// User Profile Update /////////////


    public function userprofileedit($id){

        $user = User::find($id);

        return view('admin.user_profile_edit' , compact('user'));
    }


    public function userprofileupdate(Request $request, $id){

        $user = User::find($id);

        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if($request->file('photo')){
            $file = $request->file('photo');

            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'),$filename);
            $user['photo'] = $filename;
        }

        $user->save();

        $notification = array(
            'message' => 'User Profile Updated Successfully',
            'alert-type' => 'success',
        );
        return back()->with($notification);


    }


    public function userprofiledelete($id){

        $user = User::find($id);

        if(!is_null($user)){
            $user->delete();
        }

        $notification  = array(
            'message' => 'User Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
