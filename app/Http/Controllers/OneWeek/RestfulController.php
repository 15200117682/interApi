<?php

namespace App\Http\Controllers\OneWeek;

use App\Model\CeGoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RestfulController extends Controller
{
    public function __construct()
    {
        $this->status=[
            "200"=>"ok",
            "40000"=>"必填项不能为空",
            "40001"=>"商品已存在",
            "40002"=>"未知原因，添加商品失败",
            "40003"=>"没有商品",
            "40004"=>"缺少参数",
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=CeGoodsModel::get()->toArray();
        if(empty($data)){

            return $this->fail("40003",$this->status["40003"]);

        }
        return $this->fail("200",$this->status["200"],$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data=$request->input();
        if(empty($data['name']) || empty($data['img']) || empty($data['price'])){

            return $this->fail("40000",$this->status["40000"]);

        }
        $name_info=CeGoodsModel::where(["name"=>$data['name']])->first();
        if($name_info){

            return $this->fail("40001",$this->status["40001"]);

        }
        $arr=[
            "name"=>$data['name'],
            "img"=>$data['img'],
            "price"=>$data["price"],
            "time"=>time()
        ];
        $res=CeGoodsModel::insertGetId($arr);
        if($res){

            return $this->fail("200",$this->status["200"]);

        }else{

            return $this->fail("40002",$this->status["40002"]);

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(empty($id)){

            return $this->fail("40000",$this->status["40000"]);

        }
        $data=CeGoodsModel::where(['g_id'=>$id])->first();
        return $this->fail("200",$this->status["200"],$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $data=$request->input();
        if(empty($data['name']) || empty($data['img']) || empty($data["price"])){

            return $this->fail("40004",$this->status["40004"]);

        }
        $arr=[
            "name"=>$data['name'],
            "img"=>$data['img'],
            "price"=>$data['price'],
            "time"=>time()
        ];
        $res=CeGoodsModel::where(['g_id'=>$id])->update($arr);
        if($res){

            return $this->fail("200",$this->status["200"]);

        }else{

            return $this->fail("40002",$this->status["40002"]);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(empty($id)){

            return $this->fail("40004",$this->status["40004"]);

        }
        $res=CeGoodsModel::where(["g_id"=>$id])->delete();
        if($res){

            return $this->fail("200",$this->status["200"]);

        }else{

            return $this->fail("40002",$this->status["40002"]);

        }
    }
}
