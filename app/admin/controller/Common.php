<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
class Common extends Controller
{
    protected   $mod,$system , $nav , $menudata , $cache_model,$categorys,$module,$moduleid,$orgid,$adm_id;
    public function _initialize()
    {
        //判断管理员是否登录
        if (!session('adm_id')) {
            $this->redirect('login/index');
        }
        $this->system = F('System_basic');
        $this->cache_model=array('System_basic');
        if(empty($this->sys)){
            foreach($this->cache_model as $r){
                savecache($r);
            }
        }
        define('MODULE_NAME',strtolower(request()->controller()));
        define('ACTION_NAME',strtolower(request()->action()));
        define('ORG_ID',$_SESSION['think']['adm_orgid']);
        $this->moduleid = $this->mod[MODULE_NAME];
        $this->adm_id = $_SESSION['think']['adm_id'];
        $this->orgid = $_SESSION['think']['adm_orgid'];
    }
    // 字符串截断函数
    function strlen_UTF8($str) {
        $len = strlen($str);
        $n = 0;
        for($i = 0; $i < $len; $i++) {
            $x = substr($str, $i, 1);
            $a = base_convert(ord($x), 10, 2);
            $a = substr('00000000'.$a, -8);
            if (substr($a, 0, 1) == 0) {
            }elseif (substr($a, 0, 3) == 110) {
                $i += 1;
            }elseif (substr($a, 0, 4) == 1110) {
                $i += 2;
            }
            $n++;
        }
        return $n;
    }

    // 字符串截断函数
    function subString_UTF8($str, $start, $lenth) {
        $len = strlen($str);
        $r = array();
        $n = 0;
        $m = 0;
        for($i = 0; $i < $len; $i++) {
            $x = substr($str, $i, 1);
            $a = base_convert(ord($x), 10, 2);
            $a = substr('00000000'.$a, -8);
            if ($n < $start){
                if (substr($a, 0, 1) == 0) {
                }elseif (substr($a, 0, 3) == 110) {
                    $i += 1;
                }elseif (substr($a, 0, 4) == 1110) {
                    $i += 2;
                }
                $n++;
            }else{
                if (substr($a, 0, 1) == 0) {
                    $r[ ] = substr($str, $i, 1);
                }elseif (substr($a, 0, 3) == 110) {
                    $r[ ] = substr($str, $i, 2);
                    $i += 1;
                }elseif (substr($a, 0, 4) == 1110) {
                    $r[ ] = substr($str, $i, 3);
                    $i += 2;
                }else{
                    $r[ ] = '';
                }
                if (++$m >= $lenth){
                    break;
                }
            }
        }
        return join($r);
    }
    // 中文截取无乱码
    function formatName($str, $size){
        if($str) {
            $len = $this->strlen_UTF8($str);
            $one_len = strlen($str);
            $size = $size * 1.5 * $len / ($one_len);
            if($this->strlen_UTF8($str) > $size) {
                $part1 = $this->subString_UTF8($str, 0, $size / 2);
                $part2 = $this->subString_UTF8($str, $len - ($size/2), $len);
                return  strip_tags($part1 . "..." . $part2);
            } else {
                return $str;
            }
        } else {
            return "内容为空！";
        }
    }

    //空操作
    public function _empty(){
        echo "待确认。。。";exit;
        return $this->error('空操作，返回上次访问页面中...');
    }


}
