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
        if($regData['username'] == "" || $regData['pwd'] == "" || $regData['pwd_confirm'] == "" || $regData['email'] == ""){
            $error = [
                'code' => 40000,

                'msg' => '内容为空',
                'data' => [],
            ];
            echo json_encode($error,JSON_UNESCAPED_UNICODE);die;
        }else{
            if($regData['pwd'] == $regData['pwd_confirm']){
                $pwd=password_hash($regData['pwd'],PASSWORD_DEFAULT);
                $arr=[
                    "username"=>$regData["username"],
                    "pwd"=>$pwd,
                    "email"=>$regData["email"],
                    "time"=>time()
                ];
                $userReg = UserModel::insertGetId($regData);
                if($userReg){
                    return [
                        'code' => 200,
                        'msg' => 'success 请等待跳转',
                        'data' => $userReg,
                    ];
                }else{
                    $error = [
                        'code' => 40002,
                        'msg' => '注册失败请重新尝试',
                        'data' => [],
                    ];
                    echo json_encode($error,JSON_UNESCAPED_UNICODE);die;
                }
            }else{
                $error = [
                    'code' => 40001,
                    'msg' => '请检查确认密码和密码是否一致',
                    'data' => [],
                ];
                echo json_encode($error,JSON_UNESCAPED_UNICODE);die;
            }
        }

    }
}
