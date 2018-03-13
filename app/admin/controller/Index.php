<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Input;

class Index extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index()
    {
        //导航
        if($_SESSION['think']['adm_id']) {
            $ainfo = db('admin')
                ->join('tes_auth_group','adm_groupid = agr_id','left')
                ->where('adm_id='.$_SESSION['think']['adm_id'])->find();
        }
        $authRule = [];
        $condition['aru_status'] = 1;
        $condition['aru_id'] = array('in',explode(",",$ainfo['agr_rules']));
        if(strlen($ainfo['agr_rules']) >= 3) {
            $authRule = db('auth_rule')->where($condition)->order('aru_sort')->select();
        }
        //声明数组
        $menus = array();
        foreach ($authRule as $key=>$val){
            $authRule[$key]['aru_href'] = url($val['aru_href']);
            if($val['aru_pid']==0){
                $menus[] = $val;
            }
        }
        foreach ($menus as $k=>$v){
            foreach ($authRule as $kk=>$vv){
                if($v['aru_id']==$vv['aru_pid']){
                    $menus[$k]['children'][]= $vv;
                }
            }
        }
        $this->assign('menus', json_encode($menus,true));
        return $this->fetch();
    }
    public function main(){
        $version = Db::query('SELECT VERSION() AS ver');
        $config  = [
            'url'             => $_SERVER['HTTP_HOST'],
            'document_root'   => $_SERVER['DOCUMENT_ROOT'],
            'server_os'       => PHP_OS,
            'server_port'     => $_SERVER['SERVER_PORT'],
            'server_ip'       => $_SERVER['SERVER_ADDR'],
            'server_soft'     => $_SERVER['SERVER_SOFTWARE'],
            'php_version'     => PHP_VERSION,
            'mysql_version'   => $version[0]['ver'],
            'max_upload_size' => ini_get('upload_max_filesize')
        ];
        $this->assign('config', $config);
        return $this->fetch();
    }
    public function navbar(){
        return $this->fetch();
    }
    public function nav(){
        return $this->fetch();
    }
    public function clear(){
        $R = RUNTIME_PATH;
        if ($this->_deleteDir($R)) {
            $result['info'] = '清除缓存成功!';
            $result['status'] = 1;
        } else {
            $result['info'] = '清除缓存失败!';
            $result['status'] = 0;
        }
        $result['url'] = url('Index/index');
        return $result;
    }
    private function _deleteDir($R)
    {
        $handle = opendir($R);
        while (($item = readdir($handle)) !== false) {
            if ($item != '.' and $item != '..') {
                if (is_dir($R . '/' . $item)) {
                    $this->_deleteDir($R . '/' . $item);
                } else {
                    if (!unlink($R . '/' . $item))
                        die('error!');
                }
            }
        }
        closedir($handle);
        return rmdir($R);
    }
    
}
