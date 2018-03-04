<?php
namespace app\home\controller;
use think\Controller;
use think\Input;
use think\Db;
use think\Request;
class About extends Common{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        //图片
        $imgList = db('img')->where(['open'=>1])->order('sort asc')->select();

        $this->assign('imgList', $imgList);

        //内置页图片
        $inner_com_list = db('ad')->where(array('type_id'=>5))->order('sort asc')->limit('1')->select();
        $this->assign('inner_com_list',$inner_com_list[0]);

        //内容管理 - 企业文华
        $content = db('content')->where(array('type_id'=>1))->order('sort asc')->find();
        $this->assign('content1',$content);
        //内容管理 - 关于微码
        $content = db('content')->where(array('type_id'=>13))->order('sort asc')->find();
        $this->assign('content13',$content);
        //内容管理 - 发展目标
        $content = db('content')->where(array('type_id'=>12))->order('sort asc')->find();
        $this->assign('content12',$content);

        return $this->fetch("page-show");
    }
}