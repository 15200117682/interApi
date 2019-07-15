<?php

namespace App\Http\Controllers\User;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //登陆
    public function login(Request $request){
        $loginData=$request->input();
    }

    //注册
    public function reg(Request $request){
        $regData = $request->input();
        if(empty($regData['username']) || empty($regData['pwd']) || empty($regData['pwd_confirm']) || empty($regData['email'])){
            $arr = [
                'code' => 40000,
                'msg' => '内容为空',
                'data' => [],
            ];
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }//非空验证
        if(!$regData['pwd'] == $regData['pwd_confirm']){
            $arr = [
                'code' => 40001,
                'msg' => '请检查确认密码和密码是否一致',
                'data' => [],
            ];
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }//密码错误
        $pwd=password_hash($regData['pwd'],PASSWORD_DEFAULT);
        $arr=[
            "username"=>$regData["username"],
            "pwd"=>$pwd,
            "email"=>$regData["email"],
            "time"=>time()
        ];
        $userReg = UserModel::insertGetId($arr);
            if($userReg){
                $arr= [
                    'code' => 200,
                    'msg' => 'success 请等待跳转',
                    'data' => $userReg,
                ];
                return json_encode($arr,JSON_UNESCAPED_UNICODE);
            }else{
                $arr = [
                    'code' => 40002,
                    'msg' => '注册失败请重新尝试',
                    'data' => [],
                ];
                return json_encode($arr,JSON_UNESCAPED_UNICODE);
            }
        }


}
