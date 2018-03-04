<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
class Organization extends Common{

//    public function _initialize() {
//
//    }

    /**
     * 机构基本信息管理
     * @date 2017-11-06
     * @author harry.lv
     */
    public function basic() {
        $this->orgid > 0 && $where["org_id"] = $this->orgid;
        $where["org_status"] = 1;
        $list=db('org_info')
            ->join('tes_org_detail','org_id = ode_orgid','left')
            ->where($where)
            ->select();
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加机构基本信息
     * @date 2017-11-06
     * @author harry.lv
     */
    public function basicAdd(){
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
                $data['org_chargepicurl'] = APIIMGHOST.$imgurlres['path'];
            }

            // 调用img上传接口类开始 -- 机构缩略图
            $img999 = $data['url999'];

            if($img999) {
                $filearr = explode(".",$data['thumb']);
                $houzui = $filearr[count($filearr)-1];
                $imgurlmod->base64data = $img999;
                $imgurlmod->suffix = $houzui;
                $imgurlres999 = $imgurlmod->apiupload();
                // 调用img上传接口类结束
                $data['org_img'] = APIIMGHOST.$imgurlres999['path'];
            }

            // 调用img上传接口类开始-多图片上传
            $imgarr = $data['urlarr'];
            $flag = false;
            foreach($imgarr as $va) {
                $va && $flag = true;
            }
            if(!empty($imgarr) && is_array($imgarr) && $flag) {
                $k = 0;$imgurlresarrs=[];
                foreach($imgarr as $v) {
                    $k++;
                    if($v) {
                        $filearr = explode(".", $data['thumb'.$k]);
                        $houzui = $filearr[count($filearr) - 1];
                        $imgurlmod->base64data = $v;
                        $imgurlmod->suffix = $houzui;
                        $imgurlresarr = $imgurlmod->apiupload();
                        $imgurlresarrs[] = APIIMGHOST.$imgurlresarr["path"];
                    }
                }

                // 调用img上传接口类结束
                $data['ode_picurl'] = implode(",",$imgurlresarrs);
            }
            $data['ode_tag'] = implode(",",$data['ode_tag']);
            $data['ode_selftag'] = implode(",",$data['ode_selftag']);
            $data['org_status'] = 1;
            $data['org_createtime'] = date("Y-m-d H:i:s",time());
            $m = db();
            $m->startTrans();
            $org_id = db('org_info')->insertGetId($data);
            // 构建圈子信息
            $cirdata["tmd_name"] = $data["org_name"];
            $cirdata["tmd_time"] = date("Y-m-d H:i:s",time());
            $cirdata["tmd_orgid"] = $org_id;
            $cirdata["tmd_imgurl"] = APIIMGHOST.$imgurlres999['path'];
            $cirdata["tmd_status"] = 1;
            if($org_id) {
                $data['ode_orgid'] = $org_id;
                $res1 = db('org_detail')->insert($data);
                $res2 = db('moments')->insert($cirdata);
            }
            if($org_id && $res1 && $res2) {
                $m->commit();
                $result['msg'] = '添加成功!';
                $result['url'] = url('basic');
                $result['code'] = 1;
                return $result;
            } else {
                $m->rollback();
                $result['msg'] = '添加失败!';
                $result['url'] = url('basic');
                $result['code'] = 0;
                return $result;
            }

        }else{
            return $this->fetch();
        }
    }

    /**
     * 修改机构基本信息
     * @date 2017-11-07
     * @author harry.lv
     */
    public function basicEdit(){
        $org_info=db('org_info');
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
                $data['org_chargepicurl'] = APIIMGHOST.$imgurlres['path'];
            }

            // 调用img上传接口类开始 -- 机构缩略图
            $img999 = $data['url999'];

            if($img999) {
                $filearr = explode(".",$data['thumb']);
                $houzui = $filearr[count($filearr)-1];
                $imgurlmod->base64data = $img999;
                $imgurlmod->suffix = $houzui;
                $imgurlres = $imgurlmod->apiupload();
                // 调用img上传接口类结束
                $data['org_img'] = APIIMGHOST.$imgurlres['path'];
            }

            // 调用img上传接口类开始-多图片上传
            $imgarr = $data['urlarr'];
            $flag = false;
            foreach($imgarr as $va) {
                $va && $flag = true;
            }
            if(!empty($imgarr) && is_array($imgarr) && $flag) {
                $k = 0;$imgurlresarrs=[];
                foreach($imgarr as $v) {
                    $k++;
                    if($v) {
                        $filearr = explode(".", $data['thumb'.$k]);
                        $houzui = $filearr[count($filearr) - 1];
                        $imgurlmod->base64data = $v;
                        $imgurlmod->suffix = $houzui;
                        $imgurlresarr = $imgurlmod->apiupload();
                        $imgurlresarrs[] = APIIMGHOST.$imgurlresarr["path"];
                    }
                }

                // 调用img上传接口类结束
                $data['ode_picurl'] = implode(",",$imgurlresarrs);
            }

            $data['ode_tag'] = implode(",",$data['ode_tag']);
            $data['ode_selftag'] = implode(",",$data['ode_selftag']);
            $data['org_updatetime'] = date("Y-m-d H:i:s",time());
            $where['org_id'] = input('post.org_id');
            db('org_info')->where($where)->update($data);
            $wheredetail['ode_orgid'] = input('post.org_id');
            db('org_detail')->where($wheredetail)->update($data);
            $result['msg'] = '修改成功!';
            $result['url'] = url('basic');
            $result['code'] = 1;
            return $result;
        }else{
            $org_id = input('org_id');
            $orginfo=db('org_info')
                ->join('tes_org_detail','org_id = ode_orgid','left')
                ->where(array("org_status"=>1,"org_id"=>$org_id))
                ->find();
            $orginfo["ode_picurl"] = explode(",",$orginfo["ode_picurl"]);
            $tagarr = array("全日托","半日托","月托","暑期托","家庭式服务","午餐","校园公开日","独立操场","儿童乐园","示范园");
            $k = 0;
            foreach($tagarr as $v) {
                $k++;
                $orginfo["ode_tag".$k] = in_array($v,explode(",",$orginfo["ode_tag"]));
                $orginfo["ode_selftag".$k] = in_array($v,explode(",",$orginfo["ode_selftag"]));
            }
            $this->assign('orginfo', json_encode($orginfo,true));
            return $this->fetch();
        }

    }

    /**
     * 删除机构信息
     * @date 2017-11-06
     * @author harry.lv
     */
    public function basicDel(){
        $org_id=input('org_id');
        if (empty($org_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('basic');
            exit;
        }
        $m = db();
        $m->startTrans();
        $res1 = db('org_info')->where('org_id='.$org_id)->update(array("org_status"=>0));
        if($res1) {
            $m->commit();
            db('moments')->where('tmd_orgid='.$org_id)->update(array("tmd_status"=>0));
            db('org_apply')->where('oap_orgid='.$org_id)->delete();
        } else {
            $m->rollback();
        }
        $this->redirect('basic');

    }


    /**
     * 图册管理
     * @date 2017-11-07
     * @author harry.lv
     */
    public function picturetype() {
        $list=db('org_gallery')
            ->join('tes_org_info','org_id = opi_orgid','left')
            ->select();
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加图册
     * @date 2017-11-07
     * @author harry.lv
     */
    public function picturetypeAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            $data['opi_orgid'] = $this->orgid;
            $data['opi_time'] = date("Y-m-d H:i:s",time());
            db('org_gallery')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('picturetype');
            $result['code'] = 1;
            return $result;
        }else{
            return $this->fetch();
        }
    }

    /**
     * 修改图册分类
     * @date 2017-11-07
     * @author harry.lv
     */
    public function picturetypeEdit(){
        $picmod=db('org_gallery');
        if(request()->isPost()){
            $data=input('post.');
            $data['opi_orgid'] = $this->orgid;
            $where['opi_id'] = input('post.opi_id');
            $picmod->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('picturetype');
            return $result;
        }else{
            $opi_id = input('opi_id');
            $picinfo = $picmod->where(array("opi_id"=>$opi_id))->find();
            $this->assign('picinfo', json_encode($picinfo,true));
            return $this->fetch();
        }

    }

    /**
     * 删除图册分类
     * @date 2017-11-07
     * @author harry.lv
     */
    public function picturetypeDel(){
        $opi_id=input('opi_id');
        if (empty($opi_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('picturetype');
            exit;
        }
        $opi_id = input('opi_id');
        $picinfo = db('org_photoes')->where(array("top_opiid"=>$opi_id))->find();
        if($picinfo) {
            $result['status'] = 0;
            $result['info'] = '检测到该图册下有图片，删除失败!';
            $result['url'] = url('picturetype');
            exit;
        }
        db('org_gallery')->where('opi_id='.$opi_id)->delete();
        $this->redirect('picturetype');
    }


    /**
     * 机构图片管理
     * @date 2017-11-08
     * @author harry.lv
     */
    public function picture() {
        $list=db('org_photoes')
            ->join('tes_org_gallery','opi_id = top_opiid','left')
            ->select();
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加机构图片
     * @date 2017-11-08
     * @author harry.lv
     */
    public function pictureAdd(){
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
                $data['top_picurl'] = APIIMGHOST.$imgurlres['path'];
            }
            $opiId = explode(':',$data['top_opiid']);
            $data['top_opiid'] =$opiId[1];
            $data['top_time'] = date("Y-m-d H:i:s",time());
            db('org_photoes')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('picture');
            $result['code'] = 1;
            return $result;
        }else{
            $ginfo = db('org_gallery')->select();
            $this->assign('ginfo', json_encode($ginfo,true));
            return $this->fetch();
        }
    }

    /**
     * 修改机构图片
     * @date 2017-11-07
     * @author harry.lv
     */
    public function pictureEdit(){
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
                $data['top_picurl'] = APIIMGHOST.$imgurlres['path'];
            }
            $opiId = explode(':',$data['top_opiid']);
            $data['top_opiid'] =$opiId[1];
            $data['top_time'] = date("Y-m-d H:i:s",time());
            $where['top_id'] = input('post.top_id');
            db('org_photoes')->where($where)->update($data);
            $result['msg'] = '修改成功!';
            $result['url'] = url('picture');
            $result['code'] = 1;
            return $result;
        }else{
            $top_id = input('top_id');
            $picinfo = db('org_photoes')->where(array("top_id"=>$top_id))->find();
            $this->assign('picinfo', json_encode($picinfo,true));
            $ginfo = db('org_gallery')->select();
            $this->assign('ginfo', json_encode($ginfo,true));
            return $this->fetch();
        }

    }

    /**
     * 删除机构图片
     * @date 2017-11-08
     * @author harry.lv
     */
    public function pictureDel(){
        $top_id=input('top_id');
        if (empty($top_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('picture');
            exit;
        }
        db('org_photoes')->where('top_id='.$top_id)->delete();
        $this->redirect('picture');
    }


    /**
     * 机构评论管理
     * @date 2017-11-08
     * @author harry.lv
     */
    public function comment() {
        $list=db('org_comment')
            ->join('tes_org_info','org_id = oco_orgid','left')
            ->join('tes_user_info','use_id = oco_userid','left')
            ->select();
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加机构评论信息
     * @date 2017-11-08
     * @author harry.lv
     */
    public function commentAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            $imgurlmod = new UpFiles();

            // 调用img上传接口类开始-多图片上传
            $imgarr = $data['urlarr'];
            $flag = false;
            foreach($imgarr as $va) {
                $va && $flag = true;
            }
            if(!empty($imgarr) && is_array($imgarr) && $flag) {
                $k = 0;$imgurlresarrs=[];
                foreach($imgarr as $v) {
                    $k++;
                    if($v) {
                        $filearr = explode(".", $data['thumb'.$k]);
                        $houzui = $filearr[count($filearr) - 1];
                        $imgurlmod->base64data = $v;
                        $imgurlmod->suffix = $houzui;
                        $imgurlresarr = $imgurlmod->apiupload();
                        $imgurlresarrs[] = $imgurlresarr["path"];
                    }
                }

                // 调用img上传接口类结束
                $data['oco_picurl'] = implode(",",$imgurlresarrs);
            }
            $orgId = explode(':',$data['oco_orgid']);
            $data['oco_orgid'] =$orgId[1];
            $userId = explode(':',$data['oco_userid']);
            $data['oco_userid'] =$userId[1];
            $data['oco_time'] = date("Y-m-d H:i:s",time());
            db('org_comment')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('comment');
            $result['code'] = 1;
            return $result;
        }else{
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            $userinfo = db('user_info')->select();
            $this->assign('userinfo', json_encode($userinfo,true));
            return $this->fetch();
        }
    }

    /**
     * 修改机构评论信息
     * @date 2017-11-08
     * @author harry.lv
     */
    public function commentEdit(){
        if(request()->isPost()){
            $data=input('post.');
            $imgurlmod = new UpFiles();
            // 调用img上传接口类开始-多图片上传
            $imgarr = $data['urlarr'];
            $flag = false;
            foreach($imgarr as $va) {
                $va && $flag = true;
            }
            if(!empty($imgarr) && is_array($imgarr) && $flag) {
                $k = 0;$imgurlresarrs=[];
                foreach($imgarr as $v) {
                    $k++;
                    if($v) {
                        $filearr = explode(".", $data['thumb'.$k]);
                        $houzui = $filearr[count($filearr) - 1];
                        $imgurlmod->base64data = $v;
                        $imgurlmod->suffix = $houzui;
                        $imgurlresarr = $imgurlmod->apiupload();
                        $imgurlresarrs[] = $imgurlresarr["path"];
                    }
                }

                // 调用img上传接口类结束
                $data['oco_picurl'] = implode(",",$imgurlresarrs);
            }
            $orgId = explode(':',$data['oco_orgid']);
            $data['oco_orgid'] =$orgId[1];
            $userId = explode(':',$data['oco_userid']);
            $data['oco_userid'] =$userId[1];
            $data['oco_time'] = date("Y-m-d H:i:s",time());
            $where['oco_id'] = input('post.oco_id');
            db('org_comment')->where($where)->update($data);
            $result['msg'] = '修改成功!';
            $result['url'] = url('comment');
            $result['code'] = 1;
            return $result;
        }else{
            $oco_id = input("oco_id");
            $cominfo=db('org_comment')->where(array("oco_id"=>$oco_id))->find();
            $cominfo["oco_picurl"] = explode(",",$cominfo["oco_picurl"]);
            $this->assign('cominfo', json_encode($cominfo,true));
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            $userinfo = db('user_info')->select();
            $this->assign('userinfo', json_encode($userinfo,true));
            return $this->fetch();
        }

    }

    /**
     * 删除机构评论
     * @date 2017-11-08
     * @author harry.lv
     */
    public function commentDel(){
        $oco_id=input('oco_id');
        if (empty($oco_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('comment');
            exit;
        }
        db('org_comment')->where('oco_id='.$oco_id)->delete();
        $this->redirect('comment');
    }

    /**
     * 申请管理
     * @date 2017-11-08
     * @author harry.lv
     */
    public function joincircle() {
        $list=db('org_apply')
            ->join('tes_user_info','use_id = oap_userid','left')
            ->join('tes_org_info','org_id = oap_orgid','left')
            ->select();
        $status = array(1=>"未审核",2=>"未通过",3=>"已通过");
        foreach($list as $k => $v) {
            $list[$k]["oap_status"] = $status[$v['oap_status']];
        }
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加申请
     * @date 2017-11-08
     * @author harry.lv
     */
    public function joincircleAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            $opiId = explode(':',$data['oap_userid']);
            $data['oap_userid'] =$opiId[1];
            $orgId = explode(':',$data['oap_orgid']);
            $data['oap_orgid'] =$orgId[1];
            $data['oap_status'] = 1;
            $data['oap_time'] = date("Y-m-d H:i:s",time());
            db('org_apply')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('joincircle');
            $result['code'] = 1;
            return $result;
        }else{
            $userinfo = db('user_info')->select();
            $this->assign('userinfo', json_encode($userinfo,true));
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            return $this->fetch();
        }
    }

    /**
     * 修改申请
     * @date 2017-11-08
     * @author harry.lv
     */
    public function joincircleEdit(){
        if(request()->isPost()){
            $data=input('post.');
            $opiId = explode(':',$data['oap_userid']);
            $data['oap_userid'] =$opiId[1];
            $orgId = explode(':',$data['oap_orgid']);
            $data['oap_orgid'] =$orgId[1];
            $where['oap_id'] = input('post.oap_id');
            db('org_apply')->where($where)->update($data);
            $result['msg'] = '修改成功!';
            $result['url'] = url('joincircle');
            $result['code'] = 1;
            return $result;
        }else{
            $oap_id = input('oap_id');
            $applyinfo = db('org_apply')->where(array("oap_id"=>$oap_id))->find();
            $this->assign('applyinfo', json_encode($applyinfo,true));
            $userinfo = db('user_info')->select();
            $this->assign('userinfo', json_encode($userinfo,true));
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            return $this->fetch();
        }

    }

    /**
     * 通过申请
     * @date 2017-11-09
     * @author harry.lv
     */
    public function joincircleStatus(){
        $oap_id=input('oap_id');
        $status=input('sta');
        if (empty($oap_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('joincircle');
            exit;
        }
        $data["oap_status"] = intval($status);
        db('org_apply')->where('oap_id='.$oap_id)->update($data);
        $this->redirect('joincircle');

    }

    /**
     * 撤销申请
     * @date 2017-11-08
     * @author harry.lv
     */
    public function joincircleDel(){
        $oap_id=input('oap_id');
        if (empty($oap_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('joincircle');
            exit;
        }
        db('org_apply')->where('oap_id='.$oap_id)->delete();
        $this->redirect('joincircle');
    }


    /**
     * 申请管理
     * @date 2017-11-08
     * @author harry.lv
     */
    public function dynamic() {
        $list=db('org_dynamic')
            ->join('tes_user_info','use_id = ody_userid','left')
            ->join('tes_org_info','org_id = ody_orgid','left')
            ->select();
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加申请
     * @date 2017-11-08
     * @author harry.lv
     */
    public function dynamicAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            $opiId = explode(':',$data['ody_userid']);
            $data['ody_userid'] =$opiId[1];
            $orgId = explode(':',$data['ody_orgid']);
            $data['ody_orgid'] =$orgId[1];
            $imgurlmod = new UpFiles();
            // 调用img上传接口类开始-多图片上传
            $imgarr = $data['urlarr'];
            $flag = false;
            foreach($imgarr as $va) {
                $va && $flag = true;
            }
            if(!empty($imgarr) && is_array($imgarr) && $flag) {
                $k = 0;$imgurlresarrs=[];
                foreach($imgarr as $v) {
                    $k++;
                    if($v) {
                        $filearr = explode(".", $data['thumb'.$k]);
                        $houzui = $filearr[count($filearr) - 1];
                        $imgurlmod->base64data = $v;
                        $imgurlmod->suffix = $houzui;
                        $imgurlresarr = $imgurlmod->apiupload();
                        $imgurlresarrs[] = $imgurlresarr["path"];
                    }
                }

                // 调用img上传接口类结束
                $data['ody_picurl'] = implode(",",$imgurlresarrs);
            }
            $data['ody_time'] = date("Y-m-d H:i:s",time());
            db('org_dynamic')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('dynamic');
            $result['code'] = 1;
            return $result;
        }else{
            $userinfo = db('user_info')->select();
            $this->assign('userinfo', json_encode($userinfo,true));
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            return $this->fetch();
        }
    }

    /**
     * 修改申请
     * @date 2017-11-08
     * @author harry.lv
     */
    public function dynamicEdit(){
        if(request()->isPost()){
            $data=input('post.');
            $opiId = explode(':',$data['ody_userid']);
            $data['ody_userid'] =$opiId[1];
            $orgId = explode(':',$data['ody_orgid']);
            $data['ody_orgid'] =$orgId[1];
            $imgurlmod = new UpFiles();
            // 调用img上传接口类开始-多图片上传
            $imgarr = $data['urlarr'];
            $flag = false;
            foreach($imgarr as $va) {
                $va && $flag = true;
            }
            if(!empty($imgarr) && is_array($imgarr) && $flag) {
                $k = 0;$imgurlresarrs=[];
                foreach($imgarr as $v) {
                    $k++;
                    if($v) {
                        $filearr = explode(".", $data['thumb'.$k]);
                        $houzui = $filearr[count($filearr) - 1];
                        $imgurlmod->base64data = $v;
                        $imgurlmod->suffix = $houzui;
                        $imgurlresarr = $imgurlmod->apiupload();
                        $imgurlresarrs[] = $imgurlresarr["path"];
                    }
                }

                // 调用img上传接口类结束
                $data['ody_picurl'] = implode(",",$imgurlresarrs);
            }
            $where['ody_id'] = input('post.ody_id');
            db('org_dynamic')->where($where)->update($data);
            $result['msg'] = '修改成功!';
            $result['url'] = url('dynamic');
            $result['code'] = 1;
            return $result;
        }else{
            $ody_id = input('ody_id');
            $odyinfo = db('org_dynamic')->where(array("ody_id"=>$ody_id))->find();
            $odyinfo["ody_picurl"] = explode(",",$odyinfo["ody_picurl"]);
            $this->assign('odyinfo', json_encode($odyinfo,true));
            $userinfo = db('user_info')->select();
            $this->assign('userinfo', json_encode($userinfo,true));
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            return $this->fetch();
        }

    }

    /**
     * 删除动态
     * @date 2017-11-08
     * @author harry.lv
     */
    public function dynamicDel(){
        $ody_id=input('ody_id');
        if (empty($ody_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('dynamic');
            exit;
        }
        db('org_dynamic')->where('ody_id='.$ody_id)->delete();
        $this->redirect('dynamic');
    }

    /**
     * 场景分类管理
     * @date 2017-11-08
     * @author harry.lv
     */
    public function scenetype() {
        $list=db('org_scenetype')
            ->join('tes_org_info','org_id = osc_orgid','left')
            ->select();
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加场景分类
     * @date 2017-11-08
     * @author harry.lv
     */
    public function scenetypeAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            $orgId = explode(':',$data['osc_orgid']);
            $data['osc_orgid'] =$orgId[1];
            $data['osc_time'] = date("Y-m-d H:i:s",time());
            db('org_scenetype')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('scenetype');
            $result['code'] = 1;
            return $result;
        }else{
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            return $this->fetch();
        }
    }

    /**
     * 修改图册分类
     * @date 2017-11-07
     * @author harry.lv
     */
    public function scenetypeEdit(){
        $picmod=db('org_scenetype');
        if(request()->isPost()){
            $data=input('post.');
            $orgId = explode(':',$data['osc_orgid']);
            $data['osc_orgid'] =$orgId[1];
            $where['osc_id'] = input('post.osc_id');
            $picmod->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('scenetype');
            return $result;
        }else{
            $osc_id = input('osc_id');
            $picinfo = $picmod->where(array("osc_id"=>$osc_id))->find();
            $this->assign('picinfo', json_encode($picinfo,true));
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            return $this->fetch();
        }

    }

    /**
     * 删除场景分类
     * @date 2017-11-08
     * @author harry.lv
     */
    public function scenetypeDel(){
        $osc_id=input('osc_id');
        if (empty($osc_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('scenetype');
            exit;
        }
        $osc_id = input('osc_id');
        db('org_scenetype')->where('osc_id='.$osc_id)->delete();
        $this->redirect('scenetype');
    }


    /**
     * 开放日管理
     * @date 2017-11-08
     * @author harry.lv
     */
    public function openday() {
        $list=db('org_online')
            ->join('tes_org_info','org_id = ope_orgid','left')
            ->join('tes_org_scenetype','osc_id = ope_oscid','left')
            ->select();
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 添加开放日
     * @date 2017-11-08
     * @author harry.lv
     */
    public function opendayAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            $orgId = explode(':',$data['ope_orgid']);
            $data['ope_orgid'] =$orgId[1];
            $oscId = explode(':',$data['ope_oscid']);
            $data['ope_oscid'] =$oscId[1];
            $data['ope_time'] = date("Y-m-d H:i:s",time());
            db('org_online')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('openday');
            $result['code'] = 1;
            return $result;
        }else{
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            $oscinfo = db('org_scenetype')->select();
            $this->assign('oscinfo', json_encode($oscinfo,true));
            return $this->fetch();
        }
    }

    /**
     * 修改开放日
     * @date 2017-11-08
     * @author harry.lv
     */
    public function opendayEdit(){
        if(request()->isPost()){
            $data=input('post.');
            $orgId = explode(':',$data['ope_orgid']);
            $data['ope_orgid'] =$orgId[1];
            $oscId = explode(':',$data['ope_oscid']);
            $data['ope_oscid'] =$oscId[1];
            $where['ope_id'] = input('post.ope_id');
            db('org_online')->where($where)->update($data);
            $result['msg'] = '修改成功!';
            $result['url'] = url('openday');
            $result['code'] = 1;
            return $result;
        }else{
            $ope_id = input('ope_id');
            $picinfo = db('org_online')->where(array("ope_id"=>$ope_id))->find();
            $this->assign('picinfo', json_encode($picinfo,true));
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            $oscinfo = db('org_scenetype')->select();
            $this->assign('oscinfo', json_encode($oscinfo,true));
            return $this->fetch();
        }

    }

    /**
     * 删除开放日
     * @date 2017-11-08
     * @author harry.lv
     */
    public function opendayDel(){
        $ope_id=input('ope_id');
        if (empty($ope_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('openday');
            exit;
        }
        db('org_online')->where('ope_id='.$ope_id)->delete();
        $this->redirect('openday');
    }

    /**
     * 消息推送管理
     * @date 2017-10-31
     * @author harry.lv
     */
    public function news() {
        $list=db('push_content')
            ->join('tes_org_info','org_id = pco_orgid','left')
            ->where(array("pco_type"=>1))
            ->select();
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 添加消息推送
     * @date 2017-11-09
     * @author harry.lv
     */
    public function newsAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            $orgId = explode(':',$data['pco_orgid']);
            $data['pco_orgid'] =$orgId[1];
            $data['pco_type'] = 1;
            $data['pco_time'] = date("Y-m-d H:i:s",time());
            db('push_content')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('news');
            $result['code'] = 1;
            return $result;
        }else{
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            $this->assign('title','添加推送消息');
            return $this->fetch();
        }
    }

    /**
     * 修改消息推送
     * @date 2017-11-09
     * @author harry.lv
     */
    public function newsEdit(){
        $push_content=db('push_content');
        if(request()->isPost()){
            $data=input('post.');
            $orgId = explode(':',$data['pco_orgid']);
            $data['pco_orgid'] =$orgId[1];
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
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            $this->assign('title','撤回消息修改并发送');
            return $this->fetch();
        }

    }

    /**
     * 删除消息推送
     * @date 2017-11-09
     * @author harry.lv
     */
    public function newsDel(){
        $pco_id=input('pco_id');
        if (empty($pro_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('news');
            exit;
        }
        db('push_content')->where('pco_id='.$pco_id)->delete();
        $this->redirect('news');
    }

    /**
     * 课程服务管理
     * @date 2018-01-10
     * @author harry.lv
     */
    public function lesson() {
        $data=input('get.');
        $keyword = $data["keyword"];
        $where['tol_lesson_status'] = 1;
        $pageIndex =$data['pageIndex']?$data['pageIndex']-1:0;
        $pageSize =5;
        $page = $pageIndex*$pageSize;
        if($keyword) {
            $where["tol_lesson_name"] = array("like","%".$keyword."%");
        }
        $list=db('org_lesson')->limit($page,$pageSize)->where($where)->select();

        $count=db('org_lesson')->where($where)->count();
        $pagecount = ceil($count/$pageSize);
        $this->assign('keyword',$keyword);
        $this->assign('pageIndex',$pageIndex+1);
        $this->assign('count',$pagecount);
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 添加课程服务
     * @date 2018-01-10
     * @author harry.lv
     */
    public function lessonAdd(){
        if(request()->isPost()) {
            $data=input('post.');
            $orgId = explode(':',$data['tol_orgid']);
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
                $data['tol_lesson_img'] = APIIMGHOST.$imgurlres['path'];
            }
            $data['tol_orgid'] =$orgId[1];
            $data['tol_lesson_status'] = 1;
            $data['tol_create_time'] = date("Y-m-d H:i:s",time());
            db('org_lesson')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('lesson');
            $result['code'] = 1;
            return $result;
        }else{
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            return $this->fetch();
        }
    }

    /**
     * 修改课程服务
     * @date 2018-01-10
     * @author harry.lv
     */
    public function lessonEdit(){
        $push_content=db('org_lesson');
        if(request()->isPost()){
            $data=input('post.');
            $orgId = explode(':',$data['tol_orgid']);
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
                $data['tol_lesson_img'] = APIIMGHOST.$imgurlres['path'];
            }
            $data['tol_orgid'] =$orgId[1];
            $where['tol_id'] = input('post.tol_id');
            $push_content->where($where)->update($data);
            $result['msg'] = '修改成功!';
            $result['url'] = url('lesson');
            $result['code'] = 1;
            return $result;
        }else{
            $tol_id = input('tol_id');
            $lessonlist = $push_content->where(array("tol_id"=>$tol_id))->find();
            $this->assign('lessoninfo', json_encode($lessonlist,true));
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
            return $this->fetch();
        }

    }

    /**
     * 删除课程服务
     * @date 2018-01-10
     * @author harry.lv
     */
    public function lessonDel(){
        $tol_id=input('tol_id');
        if (empty($tol_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('lesson');
            exit;
        }
        db('org_lesson')->where('tol_id='.$tol_id)->delete();
        $this->redirect('lesson');
    }

    /**
     * 查看课程服务
     * @date 2018-01-10
     * @author harry.lv
     */
    public function lessonView(){
        $usemod=db('org_lesson');
        $tol_id = input('tol_id');
        $articleinfo = $usemod->where(array("tol_id"=>$tol_id))->find();
        echo $articleinfo['tol_lesson_content'];
        exit;

    }


}