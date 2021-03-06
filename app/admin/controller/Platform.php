<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
class Platform extends Common{
    //会员列表
    public function basic(){
        $list=db('system_basic')->where(["sys_id"=>1])->select();
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 修改平台信息
     * @return mixed
     * @throws \think\Exception
     */
    public function basicEdit(){
        if(request()->isPost()) {
            $auth_group=db('system_basic');
            $data=input('post.');
            $map['sys_id'] = input('post.sys_id');
            $auth_group->where($map)->update($data);
            $result['msg'] = '平台信息修改成功!';
            $result['url'] = url('basic');
            $result['code'] = 1;
            return $result;
        }else{
            $id = input('sys_id');
            $info = db('system_basic')->where(array('sys_id' => $id))->find();
            $this->assign('info', json_encode($info,true));
            $this->assign('title','编辑平台信息');
            return $this->fetch();
        }
    }

    /**
     * 意见反馈管理
     * @date 2017-10-31
     * @author harry.lv
     */
    public function opinion() {
        $data = input('post.');
        $where = [];
        if($data["content"]) {
            $where['use_nickname'] = ["like","%".$data["content"]."%"];
            $this->assign('content',$data["content"]);
        }
        $list=db('system_proposal')
            ->join('tes_user_info','use_id = pro_userid','left')
            ->where($where)
            ->select();
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 添加意见反馈
     * @date 2017-10-31
     * @author harry.lv
     */
    public function opinionAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            $userid = explode(':',$data['pro_userid']);
            $data['pro_userid'] =$userid[1];
            $data['pro_time'] = date("Y-m-d H:i:s",time());
            db('system_proposal')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('opinion');
            $result['code'] = 1;
            return $result;
        }else{
            $info = db('user_info')->where(array('use_status' => 2))->select();
            $this->assign('userinfo', json_encode($info,true));
            $this->assign('title','添加意见反馈');
            return $this->fetch();
        }
    }

    /**
     * 修改意见反馈
     * @date 2017-10-31
     * @author harry.lv
     */
    public function opinionEdit(){
        $proposalmod=db('system_proposal');
        if(request()->isPost()){
            $data=input('post.');
            $userid = explode(':',$data['pro_userid']);
            $data['pro_userid'] =$userid[1];
            $where['pro_id'] = input('post.pro_id');
            $proposalmod->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('opinion');
            return $result;
        }else{
            $pro_id = input('pro_id');
            $proposal = $proposalmod->where(array("pro_id"=>$pro_id))->find();
            $info = db('user_info')->where(array('use_status' => 2))->select();
            $this->assign('userinfo', json_encode($info,true));
            $this->assign('proposal', json_encode($proposal,true));
            $this->assign('title','修改意见反馈');
            return $this->fetch();
        }

    }

    /**
     * 查看意见反馈
     * @date 2017-10-31
     * @author harry.lv
     */
    public function opinionView(){
        $proposalmod=db('system_proposal');
        $pro_id = input('pro_id');
        $proposal = $proposalmod->where(array("pro_id"=>$pro_id))->find();
        echo $proposal["pro_content"];
        exit;
    }

    /**
     * 删除意见反馈
     * @date 2017-10-31
     * @author harry.lv
     */
    public function opinionDel(){
        $pro_id=input('pro_id');
        if (empty($pro_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('opinion');
            exit;
        }
        db('system_proposal')->where('pro_id='.$pro_id)->delete();
        $this->redirect('opinion');
    }


    /**
     * 机构投诉管理
     * @date 2017-11-01
     * @author harry.lv
     */
    public function complaint() {
        $data = input('post.');
        $where = [];
        if($data["content"]) {
            $where['cor_firstname'] = ["like","%".$data["content"]."%"];
            $where['org_name'] = ["like","%".$data["content"]."%"];
            $where['cor_mobile'] = ["like","%".$data["content"]."%"];
            $this->assign('content',$data["content"]);
        }
        $list=db('complaint')
            ->whereor($where)
            ->join('tes_org_info','org_id = cor_orgid','left')
            ->order("cor_addtime desc")
            ->select();
        $sex = array("女","男");
        foreach($list as $k => $v) {
            $list[$k]["cor_sex"] = $sex[$v["cor_sex"]];
        }
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 修改投诉内容
     * @date 2017-11-01
     * @author harry.lv
     */
    public function complaintEdit(){
        $proposalmod=db('complaint');
        if(request()->isPost()){
            $data=input('post.');
            $userid = explode(':',$data['cor_orgid']);
            $data['cor_orgid'] =$userid[1];
            $data['cor_ip'] = $_SERVER["REMOTE_ADDR"];
            $where['cor_id'] = input('post.cor_id');
            $proposalmod->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('complaint');
            return $result;
        }else{
            $cor_id = input('cor_id');
            $proposal = $proposalmod->where(array("cor_id"=>$cor_id))->find();
            $info = db('org_info')->where(array('org_status' => 1))->select();
            $this->assign('orginfo', json_encode($info,true));
            $this->assign('proposal', json_encode($proposal,true));
            $this->assign('title','修改投诉内容');
            return $this->fetch();
        }

    }
    /**
     * 查看详情
     * @date 2017-11-24
     * @author harry.lv
     */
    public function complaintView(){
        $cor_id = input('cor_id');
        $proposal = db('complaint')->where(array("cor_id"=>$cor_id))->find();
        echo $proposal["cor_content"];
        exit;
    }

    /**
     * 删除投诉
     * @date 2017-11-01
     * @author harry.lv
     */
    public function complaintDel(){
        $cor_id=input('cor_id');
        if (empty($cor_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('complaint');
            exit;
        }
        db('complaint')->where('cor_id='.$cor_id)->delete();
        $this->redirect('complaint');
    }

    /**
     * 消息推送管理
     * @date 2017-10-31
     * @author harry.lv
     */
    public function news() {
        $data = input('post.');
        $where = [];
        if($data["content"]) {
            // 待确认
            $this->assign('content',$data["content"]);
        }
        $list=db('push_content')->where(array("pco_type"=>2))
            ->order("pco_time desc")
            ->select();
        foreach($list as $k => $v) {
            $list[$k]['pco_type'] = $v["pco_type"] == 1?  "平台" : "商户";
        }
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 添加消息推送
     * @date 2017-10-31
     * @author harry.lv
     */
    public function newsAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            $data['pco_type'] = 2;
            $data['pco_time'] = date("Y-m-d H:i:s",time());
            db('push_content')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('news');
            $result['code'] = 1;
            return $result;
        }else{
            $this->assign('title','添加推送消息');
            return $this->fetch();
        }
    }

    /**
     * 修改消息推送
     * @date 2017-10-31
     * @author harry.lv
     */
    public function newsEdit(){
        $push_content=db('push_content');
        if(request()->isPost()){
            $data=input('post.');
            $where['pco_id'] = input('post.pco_id');
            $push_content->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('news');
            return $result;
        }else{
            $pco_id = input('pco_id');
            $newslist = $push_content->where(array("pco_id"=>$pco_id))->find();
            $this->assign('newsinfo', json_encode($newslist,true));
            $this->assign('title','撤回消息修改并发送');
            return $this->fetch();
        }

    }

    /**
     * 查看详情
     * @date 2017-11-24
     * @author harry.lv
     */
    public function newsView(){
        $pco_id = input('pco_id');
        $proposal = db('push_content')->where(array("pco_id"=>$pco_id))->find();
        echo $proposal["pco_content"];
        exit;
    }

    /**
     * 删除消息推送
     * @date 2017-10-31
     * @author harry.lv
     */
    public function newsDel(){
        $pco_id=input('pco_id');
        if (empty($pco_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('news');
            echo json_encode($result);
            exit;
        }
        db('push_content')->where('pco_id='.$pco_id)->delete();
        $this->redirect('news');
    }

    /**
     * 历史搜索
     * @date 2017-11-22
     * @author harry.lv
     */
    public function historysearch() {
        $data = input('post.');
        $where = [];
        if($data["content"]) {
            $where['use_nickname'] = ["like","%".$data["content"]."%"];
            $this->assign('content',$data["content"]);
        }
        $list=db('search_log')
            ->join('tes_user_info','use_id = slo_userid','left')
            ->order("slo_time desc")
            ->where($where)->select();
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 历史搜索
     * @date 2017-11-22
     * @author harry.lv
     */
    public function historysearchDel(){
        $slo_id=input('slo_id');
        if (empty($slo_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('historysearch');
            exit;
        }
        db('search_log')->where('slo_id='.$slo_id)->delete();
        $this->redirect('historysearch');
    }

    /**
     * 修改热门推荐
     * @date 2017-11-22
     * @author harry.lv
     */
    public function searchrecommend(){
        $search_hot=db('search_hot');
        if(request()->isPost()){
            $data=input('post.');
            foreach($data['sho_content'] as $k => $v) {
//                if($v) {
                    $res = $search_hot->where(["sho_id"=>$k])->find();
                    if($res) {
                        $search_hot->where(["sho_id"=>$k])->update(["sho_content"=>$v]);
                    } else {
                        $search_hot->insert(["sho_id"=>$k,"sho_content"=>$v,"sho_time"=>date("Y-m-d H:i:s",time())]);
                    }
//                }
            }
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('searchrecommend');
            return $result;
        }else{
            $hotlist = $search_hot->limit(10)->select();
            $hotlistarr = [];
            for($i=1;$i<=10;$i++) {
                $hotlistarr[$i]["sho_id"] = $hotlist[$i-1]["sho_id"];
                $hotlistarr[$i]["sho_content"] = $hotlist[$i-1]["sho_content"];
            }
            $this->assign('hotlistarr', $hotlistarr);
            return $this->fetch();
        }

    }

    /**
     * 升级管理
     * @date 2017-11-22
     * @author harry.lv
     */
    public function upgrade(){
        $upgrade=db('upgrade');
        if(request()->isPost()){
            $data=input('post.');
            $where['tu_id'] = 1;
            $data['tu_time'] = date("Y-m-d H:i:s",time());
            $upgrade->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('upgrade');
            return $result;
        }else{
            $tu_id = 1;
            $upgrade = $upgrade->where(array("tu_id"=>$tu_id))->find();
            $this->assign('upgrade', json_encode($upgrade,true));
            return $this->fetch();
        }

    }

}