<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
class Userinfo extends Common{

    /**
     * 用户表情包管理
     * @date 2017-11-01
     * @author harry.lv
     */
    public function facegroup() {
        $data=input('post.');
        $keyword = $data["keyword"];
        if($keyword) {
            $where['use_nickname'] = array("like","%".$keyword."%");
        }
        $list=db('user_face')
            ->join('tes_user_info','use_id = ufa_userid','left')
            ->where($where)
            ->select();
        $this->assign('keyword',$keyword);
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 修改表情包
     * @date 2017-11-01
     * @author harry.lv
     */
    public function facegroupEdit(){
        $proposalmod=db('user_face');
        if(request()->isPost()){
            $data=input('post.');
            $where['ufa_id'] = input('post.ufa_id');
            $proposalmod->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('facegroup');
            return $result;
        }else{
            $ufa_id = input('ufa_id');
            $ufainfo = $proposalmod->where(array("ufa_id"=>$ufa_id))->find();
            $this->assign('ufainfo', json_encode($ufainfo,true));
            $this->assign('title','修改表情包');
            return $this->fetch();
        }

    }

    /**
     * 删除表情包
     * @date 2017-11-01
     * @author harry.lv
     */
    public function facegroupDel(){
        $ufa_id=input('ufa_id');
        if (empty($ufa_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('facegroup');
            exit;
        }
        db('user_face')->where('ufa_id='.$ufa_id)->delete();
        $this->redirect('facegroup');
    }

    /**
     * 表情包
     * @date 2017-11-01
     * @author harry.lv
     */
    public function facegroupView(){
        $ufa_userid=input('ufa_userid');
        if (empty($ufa_userid)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('facegroup');
            exit;
        }
        $list = db('user_face')->where('ufa_userid='.$ufa_userid)->select();
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 用户基本信息管理
     * @date 2017-11-01
     * @author harry.lv
     */
    public function basic() {
        $data=input('get.');
        $keyword = $data["keyword"];
        $where = " WHERE 1=1 ";
        if($keyword) {
            $where .= " AND (use_nickname like '%".$keyword."%' OR use_phone like '%".$keyword."%')";
        }
        $pageIndex =$data['pageIndex']?$data['pageIndex']-1:0;
        $pageSize =5;
        $page = $pageIndex*$pageSize;

        // 千万级数据提高查询效率
        $list = db('user_info')->query("SELECT a.`use_id`,`use_nickname`,`use_phone`,`use_picurl`,`org_name`,`use_sex`,`use_content`,`use_status`,`use_time` FROM `tes_user_info` a INNER JOIN (select b.use_id from tes_user_info as b $where order by b.use_id limit ".$page.",".$pageSize.") as tmp ON  tmp.use_id=a.use_id LEFT JOIN `tes_org_info` `tes_org_info` ON `org_id`=`use_orgid` order by use_time desc ");
        $statusarr = ['0'=>'已禁用/删除','1'=>'正常','2'=>'内部账号'];
        foreach($list as $k => $v) {
            $list[$k]['use_status'] = $statusarr[$v['use_status']];
        }
        $count = db('user_info')->query("SELECT COUNT(use_status) AS tp_count FROM `tes_user_info` $where LIMIT 1");
        $pagecount = ceil($count[0]["tp_count"]/$pageSize);
        $this->assign('keyword',$keyword);
        $this->assign('pageIndex',$pageIndex+1);
        $this->assign('count',$pagecount);
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加用户
     * @date 2017-11-01
     * @author harry.lv
     */
    public function basicAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            $sexarr = array(0=>"女",1=>"男");
            $data['use_sex'] = $sexarr[$data['use_sex']];
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
                $data['use_picurl'] = APIIMGHOST.$imgurlres['path'];
            }
            $orgId = explode(':',$data['use_orgid']);
            $data['use_orgid'] =$orgId[1];
            $data['use_status'] = 2;
            $data['use_phone'] && $data['use_password'] = md5($data['use_phone']."!#!$");
            $data['use_time'] = date("Y-m-d H:i:s",time());
            db('user_info')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('basic');
            $result['code'] = 1;
            return $result;
        }else{
            $orginfo = db('org_info')->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            $this->assign('title','添加用户');
            return $this->fetch();
        }
    }

    /**
     * 修改用户信息
     * @date 2017-11-01
     * @author harry.lv
     */
    public function basicEdit(){
        $usemod=db('user_info');
        if(request()->isPost()){
            $data=input('post.');
            $img = $data['url'];
            $sexarr = array(0=>"女",1=>"男");
            $data['use_sex'] = $sexarr[$data['use_sex']];
            if($img) {
                // 调用img上传接口类开始
                $filearr = explode(".",$data['thumb']);
                $houzui = $filearr[count($filearr)-1];
                $imgurlmod = new UpFiles();
                $imgurlmod->base64data = $img;
                $imgurlmod->suffix = $houzui;
                $imgurlres = $imgurlmod->apiupload();
                // 调用img上传接口类结束
                $data['use_picurl'] = APIIMGHOST.$imgurlres['path'];
            }
            $orgId = explode(':',$data['use_orgid']);
            $data['use_orgid'] =$orgId[1];
            $where['use_id'] = input('post.use_id');
            $usemod->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('basic');
            return $result;
        }else{
            $use_id = input('use_id');
            $useinfo = $usemod->where(array("use_id"=>$use_id))->find();
            $this->assign('useinfo', json_encode($useinfo,true));
            $orginfo = db('org_info')->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            $this->assign('title','修改用户信息');
            return $this->fetch();
        }

    }

    /**
     * 删除意见反馈
     * @date 2017-10-31
     * @author harry.lv
     */
    public function basicDel(){
        $use_id=input('use_id');
        if (empty($use_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('basic');
            exit;
        }
        db('user_info')->where('use_id='.$use_id)->delete();
        $this->redirect('basic');
    }

    /**
     * 需求记录管理
     * @date 2017-11-02
     * @author harry.lv
     */
    public function demand() {
        $list=db('demand')
            ->join('tes_user_info','use_id = dem_userid','left')
            ->select();
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 删除用户需求记录
     * @date 2017-11-02
     * @author harry.lv
     */
    public function demandDel(){
        $dem_id=input('dem_id');
        if (empty($dem_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('demand');
            exit;
        }
        db('demand')->where('dem_id='.$dem_id)->delete();
        $this->redirect('demand');
    }



}