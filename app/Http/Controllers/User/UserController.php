<?php

namespace App\Http\Controllers\User;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//use App\Tools\JWTAuth\JWTAuths;

class UserController extends Controller
{
    public function __construct()
    {
        $this->status = [
            "200" => "success",
            "40000" => "必填项不能为空",
            "40002" => "邮箱已经注册",
            "40003" => "两次输入密码不一致",
            "40004" => "未知错误，注册失败",
            "40005" => "email未注册",
            "40006" => "账号密码输入错误",
            "40007" => "非法请求"
        ];
    }

    public function fail($code = null, $msg = null, $data = null)
    {
        $response = [
            "code" => $code,
            "msg" => $msg,
            "data" => $data
        ];
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function index(Request $request)
    {
        $to = $request->input();
        $ken = decrypt($to['token']);
        $data = unserialize($ken);
        $username = $data["username"];
        if($username==""){

            return $this->fail("40007",$this->status["40007"]);

        }else{

            return $this->fail("200",$this->status["200"],$username);

        }

    }

    /**
     * 登陆
     * @param Request $request
     * @return false|string
     */
    public function login(Request $request)
    {
        $obj = new UserModel();
        $loginData = $request->input();
        //验证非空
        if (empty($loginData["email"]) || empty($loginData['pwd'])) {

            return $this->fail("40000", $this->status['40000']);

        }
        $first = UserModel::where(["email" => $loginData['email']])->first();

        //未注册
        if (!$first) {

            return $this->fail("40005", $this->status['40005']);

        }
        //验证密码
        if (!password_verify($loginData['pwd'], $first->pwd)) {

            return $this->fail("40006", $this->status['40006']);

        }

        $uid = $first->uid;
        $username = $first->username;
        $token = $obj->setsalt()->createtoken($uid, $username);

        return $this->fail("200", $this->status['200'],$token);

    }

    /**
     * 注册
     * @param Request $request
     * @return false|string
     */
    public function reg(Request $request)
    {
        $regData = $request->input();
        //非空验证
        if (empty($regData['username']) || empty($regData['pwd']) || empty($regData['pwd_confirm']) || empty($regData['email'])) {

            return $this->fail("40000", $this->status['40000']);

        }
        $first = UserModel::where(['email' => $regData["email"]])->first();

        //已注册
        if ($first) {

            return $this->fail("40002", $this->status['40002']);

        }

        //密码错误
        if (!$regData['pwd'] == $regData['pwd_confirm']) {

            return $this->fail("40003", $this->status['40003']);

        }
        $pwd = password_hash($regData['pwd'], PASSWORD_DEFAULT);
        $arr = [
            "username" => $regData["username"],
            "pwd" => $pwd,
            "email" => $regData["email"],
            "time" => time()
        ];
        $userReg = UserModel::insertGetId($arr);

        //返回结果
        if ($userReg) {

            return $this->fail("200", $this->status['200']);

        } else {

            return $this->fail("40004", $this->status['40004']);

        }
    }


}
