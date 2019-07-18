<?php

namespace App\Http\Controllers\Goods;

use App\Model\GoodsModel;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    /**
     * GoodsController constructor.
     */
    public function __construct()
    {
        $this->status = [
            "200" => "success",
            "40000"=>"未能找到商品，非法请求",
            "40008" => "未知原因，未能获取商品信息",
            "40009" =>"未能找到商品，错误的商品信息",
        ];
    }

    /**
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
     * @return false|string
     */
    public function goodslist()
    {
        $data = GoodsModel::where(["goods_status"=>1])->get()->toArray();
        $datainfo=GoodsModel::where(["goods_status"=>1])->orderBy("goods_id","desc")->limit(4)->get()->toArray();
        $arr=[
            "data"=>$data,
            "datainfo"=>$datainfo
        ];
        if ($data) {

            return $this->fail("200", $this->status["200"], $arr);

        } else {

            return $this->fail("40008", $this->status["40008"]);

        }
    }

    public function goodsdetails(Request $request){
        $goods_id=$request->input("goods_id");//接受商品id

        //非空验证
        if(empty($goods_id)){

            return $this->fail("40000", $this->status["40000"]);

        }
        $first=GoodsModel::where(["goods_id"=>$goods_id])->count();
        //商品不存在
        if(!$first){
            return $this->fail("40009", $this->status["40009"]);
        }

        $data=GoodsModel::where(["goods_id"=>$goods_id])->first()->toArray();//商品详情转数组
        return $this->fail(200,$this->status["200"],$data);
    }
}
