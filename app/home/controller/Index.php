<?php
namespace app\home\controller;
use think\Controller;
use think\Input;
use think\Db;
use think\Request;
class Index extends Common{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        $this->assign('title','首页');
        //首页推荐
        $list = db('article')->where(array('posid'=>1))->order('listorder asc,createtime desc')->limit('4')->select();
        foreach ($list as $k=>$v){
            $title_style = explode(';',$v['title_style']);
            $list[$k]['title_color'] = $title_style[0];
            $list[$k]['title_weight'] = $title_style[1];
            $title_thumb = explode(':',$title_style[2]);
            //$list[$k]['title_thumb'] = $title_thumb[1]?__PUBLIC__.$title_thumb[1]:__HOME__.'/images/portfolio-thumb/p'.($k+1).'.jpg';
            $list[$k]['title_thumb'] = __HOME__.'/images/portfolio-thumb/p'.($k+1).'.jpg';
        }
        $this->assign('list',$list);
        // 首页广告位
        $ad_list = db('ad')->where(array('type_id'=>1))->order('sort asc')->limit('3')->select();
        $this->assign('ad_list',$ad_list);

        //内置页图片
        $inner_pro_list = db('ad')->where(array('type_id'=>9))->order('sort asc')->limit('1')->select();
        $inner_com_list = db('ad')->where(array('type_id'=>5))->order('sort asc')->limit('1')->select();
        $inner_use_list = db('ad')->where(array('type_id'=>8))->order('sort asc')->limit('1')->select();

        $this->assign('inner_pro_list',$inner_pro_list[0]);
        $this->assign('inner_com_list',$inner_com_list[0]);
        $this->assign('inner_use_list',$inner_use_list[0]);

        //应用领域图片
        $imgList = db('img')->where(array('open'=>1,'type_id'=>3))->limit('4')->order('sort asc')->select();
        $this->assign('imgList', $imgList);

        //招聘信息
        $joblist= db('job')->order('add_time')->limit('3')->select();
        $this->assign('joblist',$joblist);

        //内容管理 - 关于微码
        $content = db('content')->where(array('type_id'=>13))->order('sort asc')->find();
        $this->assign('content13',$content);

        return $this->fetch();
    }
}