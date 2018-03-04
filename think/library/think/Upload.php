<?php
namespace think;
/**
 * ContactForm is the model behind the contact form.
 */
class Upload
{
    private $url;
    public $type='content';
    public $filename;
    public $subdir='/useinfo';
    public $timestamp;
    public $sign;
    public $img;


   public function __construct()
   {
       $this->url = 'http://106.14.113.124:8005/do.ashx';
   }

    /**
     * 签名
     */
    public function setSign()
    {

        $this->sign = md5($this->timestamp . $this->type . $this->filename . $this->subdir);

    }


    public  function postData()
    {
    	$res=[];
        $url = $this->url.'?type='.$this->type.'&filename='.$this->filename.'&subdir='.$this->subdir;
        $this->timestamp = time();

        $this->setSign();
        $ch = curl_init();
        $timeout = 300;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->img);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $handles = curl_exec($ch);
        curl_close($ch);
        $rs = json_decode($handles,true);
        return $rs;

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


}
