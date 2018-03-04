<?php
namespace app\home\controller;
use think\Db;
use think\Request;
use clt\Form;
class EmptyController extends Common{
    protected  $dao,$fields;
    public function _initialize()
    {
        parent::_initialize();

        $this->dao = db(DBNAME);
    }
    public function index(){
        if(DBNAME=='page'){
            $info = $this->dao->where('id',input('catId'))->find();
            $this->assign('info',$info);
            if($info['template']){
                $template = $info['template'];
            }else{
                $info['template'] = db('category')->where('id',$info['id'])->value('template_show');
                if($info['template']){
                    $template = $info['template'];
                }else{
                    $template = DBNAME.'-show';
                }
            }

            $list = db('article')->where(array('posid'=>array('neq',0)))->order('listorder asc,createtime desc')->limit('4')->select();
            foreach ($list as $k=>$v){
                $list[$k]['controller'] = db('category')->where(array('id'=>$v['catid']))->value('catdir');
                $title_style = explode(';',$v['title_style']);
                $list[$k]['title_color'] = $title_style[0];
                $list[$k]['title_weight'] = $title_style[1];
                $title_thumb = explode(':',$title_style[2]);
                $list[$k]['title_thumb'] = $title_thumb[1]?__PUBLIC__.$title_thumb[1]:__HOME__.'/images/sample-images/blog-post2.jpg';
            }
            $this->assign('list',$list);

            return $this->fetch($template);
        }else{
            if(DBNAME=='picture'){
                $setup = db('field')->where(array('moduleid'=>3,'field'=>'group'))->value('setup');
                $setup=is_array($setup) ? $setup: string2array($setup);
                $options = explode("\n",$setup['options']);
                foreach($options as $r) {
                    $v = explode("|",$r);
                    $k = trim($v[1]);
                    $optionsarr[$k]['val'] = $v[0];
                    $optionsarr[$k]['key'] = $k;
                }
                $this->assign('options',$optionsarr);
            }
            $map['catid'] = input('catId');
            if(DBNAME=='team'){
                $donation = db('donation')->order('id desc')->paginate($this->pagesize);
                $dpage = $donation->render();
                $dlist = $donation->toArray();
                $this->assign('dlist',$dlist['data']);
                $this->assign('dpage',$dpage);
                $list = $this->dao->where($map)->order('listorder asc,createtime desc')->select();
                foreach ($list as $k=>$v){
                    $list_style = explode(';',$v['title_style']);
                    $list[$k]['title_color'] =$list_style[0];
                    $list[$k]['title_weight'] =$list_style[1];
                    $title_thumb = explode(':',$list_style[2]);
                    $list[$k]['title_thumb'] = $title_thumb[1]?__PUBLIC__.$title_thumb[1]:__HOME__.'/images/sample-images/blog-post2.jpg';
                }
                $this->assign('list',$list);
            }else{
                $list = $this->dao->where($map)->order('listorder asc,createtime desc')->paginate($this->pagesize);
                // 获取分页显示
                $page = $list->render();
                $list = $list->toArray();
                foreach ($list['data'] as $k=>$v){
                    $list_style = explode(';',$v['title_style']);
                    $list['data'][$k]['title_color'] =$list_style[0];
                    $list['data'][$k]['title_weight'] =$list_style[1];
                    $title_thumb = explode(':',$list_style[2]);
                    $list['data'][$k]['title_thumb'] = $title_thumb[1]?__PUBLIC__.$title_thumb[1]:__HOME__.'/images/sample-images/blog-post2.jpg';
                }
                $this->assign('list',$list['data']);
                $this->assign('page',$page);
            }
			$cattemplate = db('category')->where('id',input('catId'))->value('template_list');
			$template =$cattemplate ? $cattemplate : DBNAME.'-list';

            //当前分类推荐
            $where['catid'] = input('catId');
            $where['posid'] = array('neq',0);
            $recommend = db('article')->where($where)->order('listorder asc,createtime desc')->limit('4')->select();
            foreach ($recommend as $k=>$v){
                $recommend[$k]['controller'] = db('category')->where(array('id'=>$v['catid']))->value('catdir');
                $title_style = explode(';',$v['title_style']);
                $recommend[$k]['title_color'] = $title_style[0];
                $recommend[$k]['title_weight'] = $title_style[1];
                $title_thumb = explode(':',$title_style[2]);
                $recommend[$k]['title_thumb'] = $title_thumb[1]?__PUBLIC__.$title_thumb[1]:__HOME__.'/images/sample-images/blog-post2.jpg';
            }
            $this->assign('recommend',$recommend);
            return $this->fetch($template);
        }
    }
    public function info(){
        $this->dao->where('id',input('id'))->setInc('hits');
        $info = $this->dao->where('id',input('id'))->find();
        $info['pic'] = $info['pic']?__PUBLIC__.$info['pic']:__HOME__."/images/sample-images/blog-post3.jpg";
        
        $title_style = explode(';',$info['title_style']);
        
        $info['title_color'] = $title_style[0];
        $info['title_weight'] = $title_style[1];
        $title_thumb = explode(':',$title_style[2]);
        $info['title_thumb'] = $title_thumb[1]?__PUBLIC__.$title_thumb[1]:__HOME__.'/images/sample-images/blog-post2.jpg';
            
            
        if(DBNAME=='picture'){
            $pics = explode(':::',$info['pics']);
            foreach ($pics as $k=>$v){
                $info['pics'][$k] = explode('|',$v);
            }
        }
        $this->assign('info',$info);

        //当前分类推荐
        $where['catid'] = input('catId');
        $where['posid'] = array('neq',0);
        $recommend = db('article')->where($where)->order('listorder asc,createtime desc')->limit('4')->select();
        foreach ($recommend as $k=>$v){
            $recommend[$k]['controller'] = db('category')->where(array('id'=>$v['catid']))->value('catdir');
            $title_style = explode(';',$v['title_style']);
            $recommend[$k]['title_color'] = $title_style[0];
            $recommend[$k]['title_weight'] = $title_style[1];
            $title_thumb = explode(':',$title_style[2]);
            $recommend[$k]['title_thumb'] = $title_thumb[1]?__PUBLIC__.$title_thumb[1]:__HOME__.'/images/sample-images/blog-post2.jpg';
        }
        $this->assign('recommend',$recommend);



        //上一篇
        $front=$this->dao->where(array('id'=>array('lt',input('id')),'catid'=>$info['catid']))->order('id desc')->limit('1')->find();
        if(!$front){
            $front['title'] = '没有了';
            $front['url'] = '#';
        }else{
            $front['url'] = url('info',array('id'=>$front['id'],'catId'=>$front['catid']));
        }
        $this->assign('front',$front);
        //下一篇
        $after=$this->dao->where(array('id'=>array('gt',input('id')),'catid'=>$info['catid']))->order('id asc')->limit('1')->find();
        if(!$after){
            $after['title'] = '没有了';
            $after['url'] = '#';
        }else{
            $after['url'] = url('info',array('id'=>$after['id'],'catId'=>$after['catid']));
        }
        $this->assign('after',$after);
        if($info['template']){
			$template = $info['template'];
		}else{
			$cattemplate = db('category')->where('id',$info['catid'])->value('template_show');
			if($cattemplate){
				$template = $cattemplate;
			}else{
				$template = DBNAME.'-show';
			}
		}
        return $this->fetch($template);
    }
    public function senMsg(){
        $data = input('post.');
        $data['addtime'] = time();
        $data['ip'] = getIp();
        db('message')->insert($data);
        $result['yes'] = "y";
        echo json_encode($result);
    }
}