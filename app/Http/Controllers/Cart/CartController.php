<?php

namespace App\Http\Controllers\Cart;

use App\Model\CartModel;
use App\Model\GoodsModel;
use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    /**
     * 状态码构造方法
     * CartController constructor.
     */
    public function __construct()
    {
        $this->status = [
            "200" => "success",
            "40000" => "参数不能为空,注意字段",
            "40004" => "未知错误，加入购物车失败",
            "40010" => "找不到商品",
            "40011" => "找不到用户",
            "40012" => "购物车为空",
        ];
    }


    /**
     * fail返回值
     * @param null $code
     * @param null $msg
     * @param null $data
     * @return false|string
     */
    public function fail($code = null, $msg = null, $data = null)
    {
        $response = [
            "code" => $code,
            "msg" => $msg,
            "data" => $data
        ];
        return json_encode($response, JSON_UNESCAPED_UNICODE);

    }


    /**
     * 商品加入购物车
     * @param Request $request
     * @return false|string
     */
    public function cartadd(Request $request)
    {
        $cartData = $request->input();

        //非空
        if (empty($cartData['token']) || empty($cartData['goods_id']) || empty($cartData['goods_price'])) {

            return $this->fail("40000", $this->status["40000"]);

        }

        $ken = decrypt($cartData['token']);
        $data = unserialize($ken);//登陆状态的用户信息
        $uid = $data["uid"];

        //找不到用户
        $userFirst = UserModel::where(["uid" => $uid])->first();
        if (!$userFirst) {

            return $this->fail("40011", $this->status["40011"]);

        }

        //找不到商品
        $first = GoodsModel::where(["goods_id" => $cartData['goods_id']])->where(["goods_status" => 1])->first();
        if (!$first) {

            return $this->fail("40010", $this->status["40010"]);

        }

        $sql = CartModel::where(["uid" => $uid])->where(["goods_id" => $cartData["goods_id"]])->first();
        if (!$sql) {
            //拼接数组
            $arr = [
                "uid" => $uid,
                "goods_id" => $cartData["goods_id"],
                "goods_price" => $cartData["goods_price"],
                "cart_time" => time()
            ];
            //加入到购物车
            $res = CartModel::insertGetId($arr);
            if ($res) {

                return $this->fail("200", $this->status['200']);

            } else {

                return $this->fail("40004", $this->status['40004']);

            }
        } else {
            //该用户想加入购物车的商品本身存在
            $res = CartModel::where(["uid" => $uid])->where(["goods_id" => $cartData["goods_id"]])->increment("goods_number");
            if ($res) {

                return $this->fail("200", $this->status['200']);

            } else {

                return $this->fail("40004", $this->status['40004']);

            }
        }

    }

    public function cartlist(Request $request)
    {
        $data = $request->input();

        //非空
        if (empty($data['token'])) {

            return $this->fail("40000", $this->status["40000"]);

        }

        $ken = decrypt($data['token']);
        $data = unserialize($ken);//登陆状态的用户信息
        $uid = $data["uid"];
        $user_first = UserModel::where(["uid" => $uid])->first();
        //找不到用户
        if (!$user_first) {

            return $this->fail("40011", $this->status["40011"]);

        }

        //购物车为空
        $datainfo = CartModel::where(["uid" => $uid])->where(["cart_status" => 1])->count();
        if ($datainfo == 0) {

            return $this->fail("40012", $this->status["40012"]);

        }else{

            $info = CartModel::where(["uid" => $uid])->where(["cart_status" => 1])
                    ->join('goods','goods.goods_id','=','cart.goods_id')
                    ->get()->toArray();
            return $this->fail("200", $this->status["200"],$info);

        }
    }

}
