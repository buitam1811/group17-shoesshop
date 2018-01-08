<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Http\Requests\UserRequest;
use App\User;
class UserController extends Controller
{
    public function getAddUser()
    {
    	return view('admin.user.add_user');
    }
    public function postAddUser(UserRequest $request)
    {
    	$this->validate($request,[
    		'txtEmail' => 'unique:users,email'
    	],[
    		"txtEmail.unique"    => "Email đã tồn tại",
    	]);

		$user           = new User;
		$user->name     = $request->txtName;
		$user->email    = $request->txtEmail;
		$user->password = bcrypt($request->txtPass);
		$user->level    = $request->rdoQuyen;
    	$user->save();
    	return redirect('admin/user/them')->with('message','Thêm thành công');
    }
    public function getListUser()
    {
    	$users = User::all();

    	return view('admin.user.list_user',compact('users'));
    }

    public function getEditUser($id)
    {
    	$user = User::find($id);
    	return view('admin.user.edit_user',compact('user'));
    }

    public function postEditUser(Request $request, $id)
    {
        $this->validate($request,[
            'txtName'        => "required|min:4",
            'txtEmail'       => "required|email|unique:users,email,".$id,
        ],[
            'txtName.required'   => "Bạn chưa nhập tên người dùng",
            'txtName.required'   => "Tên người dùng phải có ít nhất 3 kí tự",
            'txtEmail.required'  => "Bạn chưa nhập Email",
            'txtEmail.email'     => "Bạn chưa nhập đúng định dạng Email",
            "txtEmail.unique"    => "Email đã tồn tại",
        ]);

        $user        = User::find($id);
        $user->name  = $request->txtName;
        $user->email = $request->txtEmail;
        $user->level = $request->rdoQuyen;    
        $user->save();

        return redirect('admin/user/sua/'.$id)->with('message','Sửa thành công');
    }
    public function getAdminLogin()
    {
        return view('admin.login');
    }
    public function postAdminLogin(Request $request)
    {   
        $this->validate($request,[
            'txtEmail'   => "required",
            'txtPass'    => "required"
        ],[ 
            'txtEmail.required' => "Bạn chưa nhập Email",
            'txtPass.required'  => "Bạn chưa nhập Mật khẩu",
        ]);
        $email = $request->txtEmail;
        $password = $request->txtPass;
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return redirect('admin/danh-muc/danh-sach');
        }else{
            return redirect('admin/dang-nhap')->with('loi','Sai Email hoặc mật khẩu');
        }
    }
    public function  getAdmiLogout()
    {
        Auth::logout();
        return redirect('admin/dang-nhap');
    }
}