<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
class Content extends Common{

    /**
     * 用户表情包管理
     * @date 2017-11-01
     * @author harry.lv
     */
    public function slide() {
        $list=db('slide')
            ->order("car_time desc")
            ->select();
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加轮播图
     * @date 2017-11-02
     * @author harry.lv
     */
    public function slideAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            // 调用img上传接口类开始
            $img = $data['url'];
            if($img) {
                $filearr = explode(".",$data['thumb']);
                $houzui = $filearr[count($filearr)-1];
                $imgurlmod = new UpFiles();
                $imgurlmod->base64data = $img;
                $imgurlmod->suffix = $houzui;
                $imgurlres = $imgurlmod->apiupload();
                // 调用img上传接口类结束
                $data['car_picurl'] = APIIMGHOST.$imgurlres['path'];
            }
            $data['car_time'] = date("Y-m-d H:i:s",time());
            db('slide')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('slide');
            $result['code'] = 1;
            return $result;
        }else{
            return $this->fetch();
        }
    }

    /**
     * 修改轮播图
     * @date 2017-11-02
     * @author harry.lv
     */
    public function slideEdit(){
        $slide=db('slide');
        if(request()->isPost()){
            $data=input('post.');
            $img = $data['url'];
            if($img) {
                // 调用img上传接口类开始
                $filearr = explode(".",$data['thumb']);
                $houzui = $filearr[count($filearr)-1];
                $imgurlmod = new UpFiles();
                $imgurlmod->base64data = $img;
                $imgurlmod->suffix = $houzui;
                $imgurlres = $imgurlmod->apiupload();
                // 调用img上传接口类结束
                $data['car_picurl'] = APIIMGHOST.$imgurlres['path'];
            }
            $where['car_id'] = input('post.car_id');
            $slide->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('slide');
            return $result;
        }else{
            $car_id = input('car_id');
            $slideinfo = $slide->where(array("car_id"=>$car_id))->find();
            $this->assign('slideinfo', json_encode($slideinfo,true));
            return $this->fetch();
        }

    }

    /**
     * 删除轮播图
     * @date 2017-11-02
     * @author harry.lv
     */
    public function slideDel(){
        $car_id=input('car_id');
        if (empty($car_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('slide');
            exit;
        }
        db('slide')->where('car_id='.$car_id)->delete();
        $this->redirect('slide');
    }


    /**
     * 圈子文章管理
     * @date 2017-11-03
     * @author harry.lv
     */
    public function circlearticle() {
        $data=input('post.');
        $keyword = $data["keyword"];
        if($keyword) {
            $where['tmd_name'] = array("like","%".$keyword."%");
        }
        $list=db('moments_article')
            ->join('tes_moments_info','tim_id = tma_tmdid','left')
            ->join('tes_moments','tma_tmdid = tmd_id','left')
            ->order("tma_time desc")
            ->where($where)
            ->select();
        $excellent = ['0'=>'否','1'=>'是'];
        foreach($list as $k => $v) {
            $list[$k]['tma_excellent'] = $excellent[$v['tma_excellent']];
            $list[$k]['tma_istop'] = $excellent[$v['tma_istop']];
            $list[$k]['tma_content'] = $this->formatName($v['tma_content'],50);
        }
        $this->assign('keyword',$keyword);
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加文章
     * @date 2017-11-03
     * @author harry.lv
     */
    public function circlearticleAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            $data['tma_isshow'] = 1;
            $tmdid = explode(':',$data['tma_tmdid']);
            $data['tma_tmdid'] =$tmdid[1];
            $data['tma_time'] = date("Y-m-d H:i:s",time());
            db('moments_article')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('circlearticle');
            $result['code'] = 1;
            return $result;
        }else{
            $momentinfoinfo = db('moments')->select();
            $this->assign('momentinfo', json_encode($momentinfoinfo,true));
            return $this->fetch();
        }
    }

    /**
     * 修改文章信息
     * @date 2017-11-03
     * @author harry.lv
     */
    public function circlearticleEdit(){
        $usemod=db('moments_article');
        if(request()->isPost()){
            $data=input('post.');
            $tmdid = explode(':',$data['tma_tmdid']);
            $data['tma_tmdid'] =$tmdid[1];
            $where['tma_id'] = input('post.tma_id');
            $usemod->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('circlearticle');
            return $result;
        }else{
            $tma_id = input('tma_id');
            $articleinfo = $usemod->where(array("tma_id"=>$tma_id))->find();
            $this->assign('articleinfo', json_encode($articleinfo,true));
            $momentinfoinfo = db('moments')->select();
            $this->assign('momentinfo', json_encode($momentinfoinfo,true));
            return $this->fetch();
        }

    }

    /**
     * 删除文章
     * @date 2017-11-03
     * @author harry.lv
     */
    public function circlearticleDel(){
        $tma_id=input('tma_id');
        if (empty($tma_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('circlearticle');
            exit;
        }
        $m = db();
        $m->startTrans();
        $res = db('moments_article')->where('tma_id='.$tma_id)->delete();
        if($res) {
            $m->commit();
            db('moments_comment')->where(array("tmc_tmaid"=>$tma_id))->delete();
        } else {
            $m->rollback();
        }
        $this->redirect('circlearticle');
    }

    /**
     * 查看文章内容
     * @date 2017-12-29
     * @author harry.lv
     */
    public function circlearticleView(){
        $usemod=db('moments_article');
        $tma_id = input('tma_id');
        $articleinfo = $usemod->where(array("tma_id"=>$tma_id))->find();
        echo $articleinfo['tma_content'];
        exit;

    }

    /**
     * 修改文章状态
     * @date 2017-11-03
     * @author harry.lv
     */
    public function circlearticleState(){
        $map['tma_id']=input('post.tma_id');
        $status=db('moments_article')->where($map)->value('tma_isshow');//判断当前状态情况
        if($status==1){
            db('moments_article')->where($map)->setField('tma_isshow',0);
            $result['info'] = '隐藏';
        }else{
            db('moments_article')->where($map)->setField('tma_isshow',1);
            $result['info'] = '正常';
        }
        $result['status'] = 1;
        return $result;
    }

    /**
     * 评论管理
     * @date 2017-11-03
     * @author harry.lv
     */
    public function comment() {
        $data=input('post.');
        $keyword = $data["keyword"];
        if($keyword) {
            $where['use_nickname'] = array("like","%".$keyword."%");
        }
        $list=db('moments_comment')
            ->join('tes_moments_article','tma_id = tmc_tmaid','left')
            ->join('tes_user_info','use_id = tmc_useid','left')
            ->join('tes_moments','tmd_id = tma_tmdid','left')
            ->where($where)
            ->select();
        $type = ['1'=>'评论','2'=>'回复'];
        foreach($list as $k => $v) {
            $list[$k]['tmc_type'] = $type[$v['tmc_type']];
        }
        $this->assign('keyword',$keyword);
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加评论
     * @date 2017-11-03
     * @author harry.lv
     */
    public function commentAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            $tmc_tmaid = explode(':',$data['tmc_tmaid']);
            $data['tmc_tmaid'] =$tmc_tmaid[1];
            $data['tmc_isshow'] = 1;
            $tmdid = explode(':',$data['tmc_useid']);
            $data['tmc_useid'] =$tmdid[1];
            $data['tmc_time'] = date("Y-m-d H:i:s",time());
            db('moments_comment')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('circlearticle');
            $result['code'] = 1;
            return $result;
        }else{
            $tma_id = input('tma_id');
            $articleinfo = db('moments_article')->where(array("tma_id"=>$tma_id))->select();
            $this->assign('articleinfo', json_encode($articleinfo,true));
            $this->assign('article', json_encode($articleinfo[0],true));
            // 内部用户列表
            $userinfo = db('user_info')->where(array("use_status"=>2))->select();
            $this->assign('userinfo', json_encode($userinfo,true));
            // 所有用户列表
            $userlist = db('user_info')->select();
            $this->assign('userlist', $userlist);
            return $this->fetch();
        }
    }

    /**
     * 修改评论信息
     * @date 2017-11-03
     * @author harry.lv
     */
    public function commentEdit(){
        $usemod=db('moments_comment');
        if(request()->isPost()){
            $data=input('post.');
            $where['tmc_id'] = input('post.tmc_id');
            $usemod->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('comment');
            return $result;
        }else{
            $tmc_id = input('tmc_id');
            $commentinfo = $usemod->where(array("tmc_id"=>$tmc_id))->find();
            $this->assign('commentinfo', json_encode($commentinfo,true));
            return $this->fetch();
        }

    }

    /**
     * 删除评论
     * @date 2017-11-03
     * @author harry.lv
     */
    public function commentDel(){
        $tmc_id=input('tmc_id');
        if (empty($tmc_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('comment');
            exit;
        }
        db('moments_comment')->where('tmc_id='.$tmc_id)->delete();
        $this->redirect('comment');
    }

    /**
     * 修改评论状态
     * @date 2017-11-03
     * @author harry.lv
     */
    public function commentState(){
        $map['tmc_id']=input('post.tmc_id');
        $status=db('moments_comment')->where($map)->value('tmc_isshow');//判断当前状态情况
        if($status==1){
            db('moments_comment')->where($map)->setField('tmc_isshow',0);
            $result['info'] = '隐藏';
        }else{
            db('moments_comment')->where($map)->setField('tmc_isshow',1);
            $result['info'] = '正常';
        }
        $result['status'] = 1;
        return $result;
    }


    /**
     * 头条管理
     * @date 2018-1-17
     * @author harry.lv
     */
    public function headlines() {
        $data=input('get.');
        $keyword = $data["keyword"];
        if($keyword) {
            $where['ta_title'] = array("like","%".$keyword."%");
        }
        $where['ta_status'] = 0;
        $pageIndex =$data['pageIndex']?$data['pageIndex']-1:0;
        $pageSize =5;
        $page = $pageIndex*$pageSize;
        $list=db('article')->limit($page,$pageSize)
            ->order("ta_time desc")
            ->where($where)->select();
//        print_r($list);
        $type = [0=>'已发布',1=>'草稿'];
        foreach($list as $k => $v) {
            $list[$k]['ta_status'] = $type[$v['ta_status']];
            $list[$k]['ta_content'] = $this->formatName($v['ta_content'],50);
//            $list[$k]['ta_content'] = strlen($v['ta_content'])<=50 ? $v['ta_content'] : (substr($v['ta_content'],0,50).chr(0)."...");
        }

        $count=db('article')->where($where)->count();
        $pagecount = ceil($count/$pageSize);
        $this->assign('keyword',$keyword);
        $this->assign('pageIndex',$pageIndex+1);
        $this->assign('count',$pagecount);
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加头条
     * @date 2018-1-17
     * @author harry.lv
     */
    public function headlinesAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            // 调用img上传接口类开始
            $img = $data['url'];
            $imgurlmod = new UpFiles();
            if($img) {
                $filearr = explode(".",$data['thumb']);
                $houzui = $filearr[count($filearr)-1];
                $imgurlmod->base64data = $img;
                $imgurlmod->suffix = $houzui;
                $imgurlres = $imgurlmod->apiupload();
                // 调用img上传接口类结束
                $data['ta_thum_img'] = APIIMGHOST.$imgurlres['path'];
            }
            $data['ta_time'] = date("Y-m-d H:i:s",time());
            db('article')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('headlines');
            $result['code'] = 1;
            return $result;
        }else{
            return $this->fetch();
        }
    }

    /**
     * 修改头条信息
     * @date 2018-1-17
     * @author harry.lv
     */
    public function headlinesEdit(){
        $usemod=db('article');
        if(request()->isPost()){
            $data=input('post.');
            // 调用img上传接口类开始
            $img = $data['url'];
            $imgurlmod = new UpFiles();
            if($img) {
                $filearr = explode(".",$data['thumb']);
                $houzui = $filearr[count($filearr)-1];
                $imgurlmod->base64data = $img;
                $imgurlmod->suffix = $houzui;
                $imgurlres = $imgurlmod->apiupload();
                // 调用img上传接口类结束
                $data['ta_thum_img'] = APIIMGHOST.$imgurlres['path'];
            }
            $where['ta_id'] = input('post.ta_id');
            $usemod->where($where)->update($data);
            $result['msg'] = '修改成功!';
            $result['url'] = url('headlines');
            $result['code'] = 1;
            return $result;
        }else{
            $tmc_id = input('ta_id');
            $commentinfo = $usemod->where(array("ta_id"=>$tmc_id))->find();
            $this->assign('commentinfo', json_encode($commentinfo,true));
            return $this->fetch();
        }

    }

    /**
     * 删除头条
     * @date 2018-1-17
     * @author harry.lv
     */
    public function headlinesDel(){
        $ta_id=input('ta_id');
        if (empty($ta_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('headlines');
            exit;
        }
        db('article')->where('ta_id='.$ta_id)->delete();
        $this->redirect('headlines');
    }
    /**
     * 查看头条内容
     * @date 2018-1-17
     * @author harry.lv
     */
    public function headlinesView(){
        $usemod=db('article');
        $ta_id = input('ta_id');
        $articleinfo = $usemod->where(array("ta_id"=>$ta_id))->find();
        echo $articleinfo['ta_content'];
        exit;

    }

    /**
     * 打听管理
     * @date 2018-1-22
     * @author harry.lv
     */
    public function question() {
        $data=input('get.');
        $keyword = $data["keyword"];
        if($keyword) {
            $where['tp_content'] = array("like","%".$keyword."%");
        }
        $pageIndex =$data['pageIndex']?$data['pageIndex']-1:0;
        $pageSize =5;
        $page = $pageIndex*$pageSize;
        $list=db('question')->limit($page,$pageSize)->where($where)->order("tq_time desc")->select();
        foreach($list as $k => $v) {
            $org_result[$v['tq_id']] = $v["tq_org_arrid"];
        }
        if($org_result) {
            $org_str = implode(",", $org_result);
            $org_arr = explode(",", $org_str);
            $org = array_unique($org_arr);
            $orginfo = db('org_info')->field("org_id,org_name")->whereIn("org_id", $org)->select();
            foreach ($orginfo as $k => $v) {
                $orgnameinfo[$v['org_id']] = $v["org_name"];
            }
        }
        foreach($list as $k => $v) {
            $orglistarr = explode(",",$v["tq_org_arrid"]);
            $vanames = [];
            foreach($orglistarr as $va) {
                $vanames[] = $orgnameinfo[$va];
            }
            $list[$k]["tq_org_arrid"] = implode(",",$vanames);
        }
        $count=db('question')->where($where)->count();
        $pagecount = ceil($count/$pageSize);
        $this->assign('keyword',$keyword);
        $this->assign('pageIndex',$pageIndex+1);
        $this->assign('count',$pagecount);
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加打听
     * @date 2018-1-17
     * @author harry.lv
     */
    public function questionAdd(){
        if(request()->isPost()) {
            $data=input('post.');

            $data['tq_time'] = date("Y-m-d H:i:s",time());
            db('question')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('question');
            $result['code'] = 1;
            return $result;
        }else{
            // 所有用户列表
            $userlist = db('user_info')->select();
            $this->assign('userlist', $userlist);
            return $this->fetch();
        }
    }

    /**
     * 修改打听信息
     * @date 2018-1-17
     * @author harry.lv
     */
    public function questionEdit(){
        $usemod=db('question');
        if(request()->isPost()){
            $data=input('post.');
            $where['tq_id'] = input('post.tq_id');
            $usemod->where($where)->update($data);
            $result['msg'] = '修改成功!';
            $result['url'] = url('question');
            $result['code'] = 1;
            return $result;
        }else{
            $tmc_id = input('tq_id');
            $commentinfo = $usemod->where(array("tq_id"=>$tmc_id))->find();
            $this->assign('commentinfo', json_encode($commentinfo,true));
            return $this->fetch();
        }

    }

    /**
     * 删除打听
     * @date 2018-1-17
     * @author harry.lv
     */
    public function questionDel(){
        $tq_id=input('tq_id');
        if (empty($tq_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('question');
            exit;
        }
        db('question')->where('tq_id='.$tq_id)->delete();
        $this->redirect('question');
    }
    /**
     * 查看打听内容
     * @date 2018-1-17
     * @author harry.lv
     */
    public function questionView(){
        $usemod=db('question');
        $ta_id = input('tq_id');
        $articleinfo = $usemod->where(array("tq_id"=>$ta_id))->find();
        echo $articleinfo['tq_content'];
        exit;

    }
}