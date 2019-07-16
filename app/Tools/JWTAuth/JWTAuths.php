<?PHP
namespace App\Tools\JWTAuth;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JWTAuths
{
    /**
     * 单例模式 私有的静态属性
     * @var
     */
    private static $instance;

    private $iss="";

    private $aud="";

    /**
     * 单例模式 公有的静态方法
     * @return JWTAuths
     */
    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance=new self();
        }

        return self::$instance;
    }

    /**
     * 单例模式 私有的克隆方法
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * 单例模式 私有的构造方法
     * JWTAuths constructor.
     */
    private function __construct()
    {

    }

    private $uid;

    public function uid($uid){
        $this->uid=$uid;
        return $this;
    }

    private $salt="asdfghjklasdfaasdf";
    private $token;

    public function encode(){
        $time=time();
        $this->token=(new Builder())->setHeader("alg","HS256")
                              ->setIssuedAt($this->iss)
                              ->setAudience($this->aud)
                              ->setIssuedAt($time)
                              ->setExpiration($time+3700)
                              ->set("uid",$this->uid)
                              ->sign(new Sha256(),$this->salt)
                              ->getToken();
        return $this;
    }

    public function token(){
        $token=(string)$this->token;
        return $token;
    }


}