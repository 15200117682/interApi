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
            "40008" => "未知原因，未能获取商品信息"
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
}
