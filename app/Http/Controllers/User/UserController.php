<?php

namespace App\Http\Controllers\User;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //登陆
    public function login(Request $request){
        $loginData=$request->input();
        if(empty($loginData["email"]) || empty($loginData['pwd'])){
            $arr = [
                'code' => 40000,
                'msg' => '内容为空',
                'data' => [],
            ];
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }//验证非空
        $first=UserModel::where(["email"=>$loginData['email']])->first();
        if(!$first){
            $arr = [
                'code' => 40003,
                'msg' => '该邮箱未注册，请注册后登陆',
                'data' => [],
            ];
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }//邮箱未注册
        $token=mb_substr( md5( $first->uid.Str::random(8).mt_rand(11,999999) ) , 10 , 10 );
        $arr = [
            'code' => 40003,
            'msg' => '登陆成功',
            'data' => [
                "token"=>$token
            ],
        ];
        return json_encode($arr,JSON_UNESCAPED_UNICODE);

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
