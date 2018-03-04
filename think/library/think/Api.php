<?php
namespace think;

/**
 * ContactForm is the model behind the contact form.
 */
class Api
{
    private $appid = 100401;
    private $isEncrypt = 0;
    private $version = "v1";
    private $checkkey = "11a6c8704a51467dcc61e6dc6d2e97e1";
//    private $url ="/json/oneway/NEasyServiceRequest";
    private $url ="http://192.168.8.116:40001/json/oneway/NEasyServiceRequest";

    public $action;
    public $type;
    public $data;
    public $sign;
    public $clientip;
    public $timestamp ;



    /**
     *构造方法
     */

    public  function __construct(){

//     $this->url=constant('apiurl').$this->url;
     }

    /**
     * 签名
     */
    public function setSign()
    {

        $this->sign = md5($this->appid . $this->version . $this->type . $this->action . $this->data . $this->timestamp . $this->checkkey);

    }

    /**
     * 格式化请求数据
     */
    public function formatData()
    {
        return json_encode(
            Array("appid" => $this->appid,
                "timestamp" => $this->timestamp,
                "type" => $this->type,
                "action" => $this->action,
                "data" => $this->data,
                "isencrypt" => $this->isEncrypt,
                "version" => $this->version,
                "clientip" => $this->clientip,
                "sign" => $this->sign
            ));

    }

    public  function postData()
    {
    	$res=[];
        $url = $this->url;
        $this->timestamp = time();
        $this->clientip =  $_SERVER["REMOTE_ADDR"];
        $this->setSign();
        $data = $this->formatData();
//        print_r($data);
        $ch = curl_init();
        $timeout = 300;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $handles = curl_exec($ch);
        curl_close($ch);
        
        $rs = json_decode($handles,true);
       if($rs['Code']==200){
       	 $res['status']=1;
       	 $res['msg']='成功';     	 
       	 $body = isset($rs['Body']) ? $rs['Body']:null;
       	 
       	 if($body) $res['body'] = json_decode($body,true);
       	 
       } else{
       	 $res['status']=0;
         $res['Code']=$rs['Code'];
         $msg = $this->getMsg($rs['Code']);
       	 $res['msg']= $msg?$msg:$rs['Msg'];

       }

        return $res;

    }


    public  function getData($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;


    }
    
    //获取错误信息
    public function getMsg($code)
    {
    	$codeMsg = array(
    			'200'=>'成功',
    			'400'=>'失败',	
    			'401'=>'请求参数错误',
    			'404'=>'数据不存在',
    	);
    	
    	$msg = '';
    	foreach($codeMsg as $k=>$v){
    		if($k == $code) $msg = $v;   		
    	}
    	
    	return $msg;
    }
    
      
}
