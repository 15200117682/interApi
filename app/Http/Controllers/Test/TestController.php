<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    /**
     * 状态码
     * TextController constructor.
     */
    public function __construct()
    {
        $this->status = [
            1000 => '登录成功',
            200 => "成功",
            201 => "注册失败",
            202 => "用户已存在",
            203 => "用户名或密码错误",
            204 => "缺少请求参数",
            205 => "登录过期",
            206 => "数据异常",
        ];
    }

    /**
     * 提示
     * @param null $code
     * @param null $msg
     * @param null $data
     * @return array
     */
    public function fail($code = null, $msg = null, $data = null)
    {

        $response = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];
        return $response;
    }

    /**
     * 注册
     * @param Request $request
     * @return array
     */
    public function register(Request $request)
    {
        $data = $request->input();
        if (empty($data['name']) || empty($data['pwd'])) {

            return $this->fail(204, $this->status['204']);

        }

        $userInfo = User::where('name', $data['name'])->first();


        if (!empty($userInfo)) {

            return $this->fail(202, $this->status['202']);
        }

        $result = DB::table('user')->insert($data);

        if ($result) {

            return $this->fail(200, $this->status['200']);

        } else {

            return $this->fail(201, $this->status['201']);
        }
    }
}
