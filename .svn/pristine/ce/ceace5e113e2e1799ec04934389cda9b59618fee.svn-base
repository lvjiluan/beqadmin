<?php
namespace app\home\controller;
use think\Input;
use think\Db;
use think\Request;
use think\Controller;
class Common extends Controller{
    protected $pagesize;
    public $cate;
    public function _initialize(){
        // 如果是手机跳转到 手机模块
        /*if(true == isMobile()){
            $this->redirect('mobile/Index/index');
        }*/
        $sys = F('System');
        $this->assign('sys',$sys);
        //获取控制方法
        $request = Request::instance();
        $action = $request->action();
        $controller = $request->controller();
        $this->assign('action',($action));
        $this->assign('controller',strtolower($controller));
        define('MODULE_NAME',strtolower($controller));
        define('ACTION_NAME',strtolower($action));
        //主导航
        $category = db('category');
        $thisCat = $category->where('id',input('catId'))->find();
        $this->assign('title',$thisCat['catname']);
        $this->assign('keywords',$thisCat['keywords']);
        $this->assign('description',$thisCat['description']);
        define('DBNAME',strtolower($thisCat['module']));
        $this->pagesize = $thisCat['pagesize']>0 ? $thisCat['pagesize'] : '10';
        $cate = $category->where('parentid',0)->order('listorder')->select();
        foreach ($cate as $k=>$v){
            if($v['module']=='page'){
                $cate[$k]['first'] = 'index';
                $cate[$k]['firstId'] =$v['id'];
            }else{
                $sub = $category->where('parentid',$v['id'])->order('listorder')->select();
                if($sub){
                    $cate[$k]['first'] = 'index';
                    $cate[$k]['firstId'] = $sub['0']['id'];
                    $cate[$k]['sub'] =   $sub;
                    if($sub) {
                        foreach ($sub as $key=>$val) {
                            if ($val['module'] == 'page') {
                                $cate[$k]['sub'][$key]['first'] = 'index';
                                $cate[$k]['sub'][$key]['firstId'] = $val['id'];
                            } else {
                                $suber = $category->where('parentid', $val['id'])->order('listorder')->select();
                                if ($suber) {
                                    $cate[$k]['sub'][$key]['first'] = 'index';
                                    $cate[$k]['sub'][$key]['firstId'] = $suber['0']['id'];
                                    $cate[$k]['sub'][$key]['suber'] = $suber;
                                } else {
                                    $cate[$k]['sub'][$key]['first'] = 'index';
                                    $cate[$k]['sub'][$key]['firstId'] = $val['id'];
                                }
                            }
                        }
                    }
                }else{
                    $cate[$k]['first'] = 'index';
                    $cate[$k]['firstId'] =$v['id'];
                }
            }
        }
        $protree = [];
        $this->cate = $cate;
        // 目前只有产品中心有二级三级分类
        foreach($cate as $K => $v) {
            if($v["id"] == 1) {
                $protree = $v['sub'];
            }
        }
        $this->assign('protree',$protree);
        $this->assign('category',$cate);

        //广告
        $adList = db('ad')->where(['type_id'=>1,'open'=>1])->order('sort asc')->limit('4')->select();
        $this->assign('adList', $adList);
        //友情链接
		$linkList = db('link')->where('open',1)->order('sort asc')->select();
		$this->assign('linkList', $linkList);
		//碎片
		$contact = db('debris')->where('id',3)->find();
		$this->assign('contact', $contact);
        //系统信息
        $system = db('system')->where('id',1)->find();
        $this->assign('system', $system);

    }
    public function _empty(){
        return $this->error('空操作，返回上次访问页面中...');
    }
}