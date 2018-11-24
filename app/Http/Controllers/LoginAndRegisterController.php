<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Users\UsersReporitoryInterface;
use Illuminate\Support\Facades\Auth;

class LoginAndRegisterController extends Controller
{
    //
    protected $UserRepository;
    public function __construct(UsersReporitoryInterface $usersReporitory)
    {
        $this->UserRepository = $usersReporitory;
    }

    public function getLogin(){
        return view("Login");
    }
    public function postLogin(Request $request){
        if(Auth::attempt(["name"=>$request->username,"password"=>$request->password])){
            return redirect("admin/DashBoard")->with("thong_bao","Login success,welcome adminstator!");
        }else{
            return redirect()->back()->with("thong_bao","Login false, please check again username or password");
        }
    }
}
