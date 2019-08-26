<?php

namespace App\Http\Controllers\OneWeek;

use App\Model\CartModel;
use App\Model\CeGoodsModel;
use App\Model\CoryModel;
use App\Model\GoodsModel;
use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class OneWeekController extends Controller
{
    public function __construct()
    {
        $this->status=[
            "200"=>"ok",
            "201"=>"获取成功",
            "40000"=>"必填项不能为空",
            "40001"=>"商品已存在",
            "40002"=>"未知原因，添加商品失败",
            "40003"=>"没有商品",
            "40004"=>"缺少参数",
            "40005"=>"账号不存在",
            "40006"=>"账号密码错误"
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
     * 商品添加
     * @param Request $request
     * @return false|string
     */
    public function insert(Request $request){
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
     * 商品展示
     * @return false|string
     */
    public function select(){
        $data=CeGoodsModel::get()->toArray();
        if(empty($data)){

            return $this->fail("40003",$this->status["40003"]);

        }
        return $this->fail("200",$this->status["200"],$data);
    }

    /**
     * 查询一条数据
     * @param Request $request
     * @return false|string
     */
    public function goodsfind(Request $request){
        $id=$request->input();
        if(empty($id['g_id'])){

            return $this->fail("40000",$this->status["40000"]);

        }
        $data=CeGoodsModel::where(['g_id'=>$id["g_id"]])->first();
        return $this->fail("200",$this->status["200"],$data);
    }
    /**
     * 商品删除
     * @param Request $request
     * @return false|string
     *
     */
    public function delete(Request $request){
        $id=$request->input();
        if(empty($id['g_id'])){

            return $this->fail("40004",$this->status["40004"]);

        }
        $res=CeGoodsModel::where(["g_id"=>$id['g_id']])->delete();
        if($res){

            return $this->fail("200",$this->status["200"]);

        }else{
            return $this->fail("40002",$this->status["40002"]);
        }
    }

    /**
     * 商品修改
     * @param Request $request
     * @return false|string
     */
    public function update(Request $request){
        $data=$request->input();
        if(empty($data['g_id']) || empty($data['name']) || empty($data['img']) || empty($data["price"])){

            return $this->fail("40004",$this->status["40004"]);

        }
        $arr=[
            "name"=>$data['name'],
            "img"=>$data['img'],
            "price"=>$data['price'],
            "time"=>time()
        ];
        $res=CeGoodsModel::where(['g_id'=>$data['g_id']])->update($arr);
        if($res){

            return $this->fail("200",$this->status["200"]);

        }else{

            return $this->fail("40002",$this->status["40002"]);

        }

    }

    /**
     * 文件上传
     */
    public function uploadadd(){
        return view("upload.uploadadd");
    }

    //对称加密解密
    public function encrypt(){
        //var_dump(md5("1812"."袁帅"."100"));exit;
        $str="sha1tianwen";
        $key="1234567890123456";
        $iv="sginfdcvbnfgvbdc";
        /*$enStr=openssl_encrypt($str,"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv);//加密
        var_dump($enStr);echo "<br/>";*/
        $enStr1="PcxKE55kH/MeS+AxIGfZF71+WhQadHAoD3lYqp9qBU4dDAZwtEmUrzGSHkYebtsy";
        $enStr=base64_decode($enStr1);
        $deStr=openssl_decrypt($enStr,"aes-128-ecb",$key,OPENSSL_RAW_DATA);//解密
        var_dump($deStr);
    }

    public function Noencrypt(){
        $str="添雯sha1";
        $key=openssl_get_publickey("file://".storage_path("keys/rsa_public_key.pem"));//获取公钥
        openssl_public_encrypt($str,$encrypt,$key);//加密
        echo "加密的结果：";var_dump($encrypt);echo "<br/>";
        $keys=openssl_get_privatekey("file://".storage_path("keys/rsa_private.pem"));//获取私钥
        openssl_private_decrypt($encrypt,$decrypt,$keys);//解密
        echo "解密的结果：";var_dump($decrypt);
    }

    public function shubao(){
        $str="宝贝儿你真美";

        //加密
        $enstr=base64_encode($str);
        $length=strlen($enstr);
        $arr="";
        for ($i=0;$i<$length;$i++){
            $k=ord($enstr[$i]);
            $v=$k+3;
            $arr.=chr($v);
        }

        //解密
        $dearr="";
        $strlen=strlen($arr);
        for ($i=0;$i<$strlen;$i++){
            $k=ord($arr[$i]);
            $v=$k-3;
            $dearr.=chr($v);
        }
        $res=base64_decode($dearr);
        echo $res;
    }


    public function foreignUrl(){
        //$url2=$_SERVER["HTTP_HOST"];
        $url="https://interapi.qiong001.com/ceshi/posts";
        $key=openssl_get_privatekey("file://".storage_path("keys/rsa_private.pem"));//获取私钥
        openssl_private_encrypt($url,$encrypt,$key);//加密
        $data=base64_encode($encrypt);
        $arr=[
            "encrypt"=>$data
        ];
        return $this->fail("201",$this->status["201"],$arr);

    }

    public function foreignDoUrl(){
        $url="https://interapi.qiong001.com/ceshi/posts";
        $key="onetwothree";
        $enStr=openssl_encrypt($url,"AES-128-ECB",$key,OPENSSL_RAW_DATA);//ECB模式加密数据
        $data=base64_encode($enStr);
        $arr=[
            "data"=>$data
        ];
        return $this->fail("201",$this->status["201"],$arr);

    }


    //登陆
    public function login(Request $request){
        $loginData=$request->input();
        //空
        if(empty($loginData['email']) || empty($loginData["pwd"])){

            return $this->fail("40000",$this->status["40000"]);

        }
        //账号
        $first=UserModel::where(["email"=>$loginData['email']])->first();
        if(!$first){

            return $this->fail("40005",$this->status["40005"]);

        }
        //验证密码
        if (!password_verify($loginData['pwd'], $first->pwd)) {

            return $this->fail("40006", $this->status['40006']);

        }
        $obj = new UserModel();
        $uid = $first->uid;
        $username = $first->username;
        $token = $obj->setsalt()->createtoken($uid, $username);
        return $this->fail("200", $this->status['200'],$token);
    }

    /**
     * 热卖商品列表
     */
    public function goodshot(){

        $data=Cache::get("goodshot");
        //$get=GoodsModel::take(4)->get();
        if(empty($data)){
            $data=GoodsModel::inRandomOrder()->take(4)->get()->toArray();
            Cache::put("goodshot",$data);

        }

        return $this->fail("200",$this->status["200"],$data);
    }

    public function detail(Request $request){
        $goods_id=$request->input();
        $goodsData=GoodsModel::where(["goods_id"=>$goods_id['goods_id']])->first()->toArray();

        return $this->fail("200",$this->status["200"],$goodsData);
    }

    //分类查询
    public function cartcory(){
        $cartData=CoryModel::get()->toArray();
        return $this->fail("200",$this->status["200"],$cartData);
    }

    //根据分类查商品
    public function corygoods($c_id){
        $cartData=GoodsModel::where(["cate_id"=>$c_id])->get()->toArray();
        return $this->fail("200",$this->status["200"],$cartData);
    }




}
