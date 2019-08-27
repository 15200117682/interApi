<?php

namespace App\Http\Controllers\FourWeek;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FourWeekController extends Controller
{
    public function __construct()
    {
        $this->status=[
            "200"=>"ok",
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

    public function weather(Request $request){
        $city=$request->input("city");
        if(empty($city)){

            return $this->fail("40000",$this->status["40000"]);
            
        }
        $urls="http://api.k780.com:88/";
        $parameter=[
            "app"=>"weather.today",
            "appkey"=>"10003",
            "sign"=>"b59bc3ef6191eb9f747dd4e83c99f2a4",
            "format"=>"json",
            "weaid"=>$city,
        ];
        $datas=$this->curl($urls,$parameter);
        $datas=json_decode($datas,JSON_UNESCAPED_UNICODE);
        $data=$datas["result"];
        $dataInfo=[
            "citynm"=>$data["citynm"],
            "temperature_curr"=>$data["temperature_curr"],
            "temperature"=>$data["temperature"],
            "wind"=>$data["wind"],
            "weather"=>$data["weather"],
            "week"=>$data["week"],
        ];

        return $this->fail("200",$this->status["200"],$dataInfo);

    }

    public function curl($urls,$parameter){
        //初始化
        $ch=curl_init();
        //设置参数
        curl_setopt($ch,CURLOPT_URL,$urls);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameter));
        //执行会话
        $data=curl_exec($ch);
        $errno=curl_errno($ch);
        if($errno){
            $error=curl_error($ch);
            dd($error);
        }
        //关闭会话
        curl_close($ch);
        return $data;
    }
}
