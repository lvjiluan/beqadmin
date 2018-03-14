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

        $data=input('get.');
        $keyword = $data["keyword"];
        $pageIndex =$data['pageIndex']?$data['pageIndex']-1:0;
        $pageSize =6;
        $page = $pageIndex*$pageSize;
        if($keyword) {
            $where["org_name"] = array("like","%".$keyword."%");
        }

        $list=db('org_info')
            ->join('tes_org_detail','org_id = ode_orgid','left')
            ->limit($page,$pageSize)
            ->where($where)
            ->select();

        $type = array(0=>"系统消息", 1=>"机构评论回复", 2=>"商家消息");
        $status = array(0=>"未读取", 1=>"已读", 2=>"已删除");
        foreach($list as $k => $v) {
            $list[$k]["inf_type"] = $type[$v["inf_type"]];
            $list[$k]["inf_status"] = $status[$v["inf_status"]];
        }

        $count=db('org_info')
            ->join('tes_org_detail','org_id = ode_orgid','left')
            ->where($where)->count();
        $pagecount = ceil($count/$pageSize);
        $this->assign('keyword',$keyword);
        $this->assign('pageIndex',$pageIndex+1);
        $this->assign('count',$pagecount);
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
            print_r(input('post.'));exit;
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
            $orginfo["ode_tag"] = explode(",",$orginfo["ode_tag"]);
            $orginfo["ode_selftag"] = explode(",",$orginfo["ode_selftag"]);
//            $tagarr = array("全日托","半日托","月托","暑期托","家庭式服务","午餐","校园公开日","独立操场","儿童乐园","示范园");
//            $k = 0;
//            foreach($tagarr as $v) {
//                $k++;
//                $orginfo["ode_tag".$k] = in_array($v,explode(",",$orginfo["ode_tag"]));
//                $orginfo["ode_selftag".$k] = in_array($v,explode(",",$orginfo["ode_selftag"]));
//            }
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
        $where["org_status"] = 1;
        $this->orgid > 0 && $where["org_id"] = $this->orgid;
        $list=db('org_gallery')
            ->join('tes_org_info','org_id = opi_orgid','left')
            ->where($where)
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
            $orgId = explode(':',$data['opi_orgid']);
            $data['opi_orgid'] =$orgId[1];
            $data['opi_time'] = date("Y-m-d H:i:s",time());
            db('org_gallery')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('picturetype');
            $result['code'] = 1;
            return $result;
        }else{
            $where["org_status"] = 1;
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
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
    public function picturetypeEdit(){
        $picmod=db('org_gallery');
        if(request()->isPost()){
            $data=input('post.');
            $orgId = explode(':',$data['opi_orgid']);
            $data['opi_orgid'] =$orgId[1];
            $where['opi_id'] = input('post.opi_id');
            $picmod->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('picturetype');
            return $result;
        }else{
            $opi_id = input('opi_id');
            $picinfo = $picmod->where(array("opi_id"=>$opi_id))->find();
            $where["org_status"] = 1;
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $orginfo = db('org_info')->where($where)->select();
            $this->assign('orginfo', json_encode($orginfo,true));
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
        $data=input('get.');
        $keyword = $data["keyword"];
        $pageIndex =$data['pageIndex']?$data['pageIndex']-1:0;
        $pageSize =10;
        $page = $pageIndex*$pageSize;
        if($keyword) {
            $where["opi_title"] = array("like","%".$keyword."%");
        }
        $list=db('org_gallery')
            ->join('tes_org_info','opi_orgid = org_id','left')
            ->limit($page,$pageSize)->where($where)->select();
        $type =array("全部","环境");
        foreach($list as $k => $v) {
            $list[$k]["opi_type"] = $type[$v["opi_type"]];
        }

        $count=db('org_gallery')->where($where)->count();
        $pagecount = ceil($count/$pageSize);
        $this->assign('keyword',$keyword);
        $this->assign('pageIndex',$pageIndex+1);
        $this->assign('count',$pagecount);
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
                $data['opi_img_url'] = APIIMGHOST.$imgurlres['path'];
            }
            $opiId = explode(':',$data['opi_orgid']);
            $data['opi_orgid'] =$opiId[1];
            $data['opi_time'] = date("Y-m-d H:i:s",time());
            db('org_gallery')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('picture');
            $result['code'] = 1;
            return $result;
        }else{
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $ginfo = db('org_info')
                ->where($where)
                ->select();
//            foreach($ginfo as $k => $v) {
//                $ginfo[$k]["opi_title"] = $v["opi_title"]."-".$v["org_name"];
//            }
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
                $data['opi_img_url'] = APIIMGHOST.$imgurlres['path'];
            }
            $opiId = explode(':',$data['opi_orgid']);
            $data['opi_orgid'] =$opiId[1];
            $where['opi_id'] = input('post.opi_id');
            db('org_gallery')->where($where)->update($data);
            $result['msg'] = '修改成功!';
            $result['url'] = url('picture');
            $result['code'] = 1;
            return $result;
        }else{
            $opi_id = input('opi_id');
            $picinfo = db('org_gallery')->where(array("opi_id"=>$opi_id))->find();
            $this->assign('picinfo', json_encode($picinfo,true));
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            $ginfo = db('org_info')
                ->where($where)
                ->select();
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
        $opi_id=input('opi_id');
        if (empty($opi_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('picture');
            exit;
        }
        db('org_gallery')->where('opi_id='.$opi_id)->delete();
        $this->redirect('picture');
    }


    /**
     * 机构评论管理
     * @date 2017-11-08
     * @author harry.lv
     */
    public function comment() {
        $data=input('get.');
        $keyword = $data["keyword"];
        $pageIndex =$data['pageIndex']?$data['pageIndex']-1:0;
        $pageSize =10;
        $page = $pageIndex*$pageSize;
        if($keyword) {
            $where["oco_content"] = array("like","%".$keyword."%");
        }
        $list=db('org_comment')
            ->join('tes_org_info','org_id = oco_orgid','left')
            ->join('tes_user_info','use_id = oco_userid','left')
            ->order("oco_time desc")
            ->limit($page,$pageSize)->where($where)->select();

        $count=db('org_comment')
            ->join('tes_org_info','org_id = oco_orgid','left')
            ->join('tes_user_info','use_id = oco_userid','left')
            ->order("oco_time desc")
            ->where($where)->count();
        $pagecount = ceil($count/$pageSize);
        $this->assign('keyword',$keyword);
        $this->assign('pageIndex',$pageIndex+1);
        $this->assign('count',$pagecount);
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
        $data=input('get.');
        $keyword = $data["keyword"];
        $pageIndex =$data['pageIndex']?$data['pageIndex']-1:0;
        $pageSize =10;
        $page = $pageIndex*$pageSize;
        if($keyword) {
            $where["tmd_name"] = array("like","%".$keyword."%");
        }
        $list=db('moments_user')
            ->join('tes_user_info','use_id = tmu_user_id','left')
            ->join('moments','tmd_id = tmu_moments_id','left')
            ->limit($page,$pageSize)->where($where)->select();
        $status =array("审核中","审核通过","审核失败","退出圈子");
        foreach($list as $k => $v) {
            $list[$k]["tmu_status"] = $status[$v["tmu_status"]];
        }
        $count=db('moments_user')
            ->join('tes_user_info','use_id = tmu_user_id','left')
            ->join('moments','tmd_id = tmu_moments_id','left')
            ->where($where)->count();
        $pagecount = ceil($count/$pageSize);
        $this->assign('keyword',$keyword);
        $this->assign('pageIndex',$pageIndex+1);
        $this->assign('count',$pagecount);
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
            $opiId = explode(':',$data['tmu_user_id']);
            $data['tmu_user_id'] =$opiId[1];
            $orgId = explode(':',$data['tmu_moments_id']);
            $data['tmu_moments_id'] =$orgId[1];
            $data['tmu_create_time'] = date("Y-m-d H:i:s",time());
            db('moments_user')->insert($data);
            $result['msg'] = '添加成功!';
            $result['url'] = url('joincircle');
            $result['code'] = 1;
            return $result;
        }else{
            $userinfo = db('user_info')->select();
            $this->assign('userinfo', json_encode($userinfo,true));
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            if($where["org_id"]) {
                $orginfo = db('org_info')->where($where)->find();
            }
            $orginfo["org_id"] && $wherec["tmd_id"] = $orginfo["org_id"];
            $wherec["tmd_status"] = 1;
            $minfo = db('moments')->where($wherec)->select();
            $this->assign('minfo', json_encode($minfo,true));
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
            $opiId = explode(':',$data['tmu_user_id']);
            $data['tmu_user_id'] =$opiId[1];
            $orgId = explode(':',$data['tmu_moments_id']);
            $data['tmu_moments_id'] =$orgId[1];
            $data['tmu_update_time'] = date("Y-m-d H:i:s",time());
            $where['tmu_id'] = input('post.tmu_id');
            db('moments_user')->where($where)->update($data);
            $result['msg'] = '修改成功!';
            $result['url'] = url('joincircle');
            $result['code'] = 1;
            return $result;
        }else{
            $tmu_id = input('tmu_id');
            $applyinfo = db('moments_user')->where(array("tmu_id"=>$tmu_id))->find();
            $this->assign('applyinfo', json_encode($applyinfo,true));
            $userinfo = db('user_info')->select();
            $this->assign('userinfo', json_encode($userinfo,true));
            $this->orgid > 0 && $where["org_id"] = $this->orgid;
            $where["org_status"] = 1;
            if($where["org_id"]) {
                $orginfo = db('org_info')->where($where)->find();
            }
            $orginfo["org_id"] && $wherec["tmd_orgid"] = $orginfo["org_id"];
            $wherec["tmd_status"] = 1;
            $minfo = db('moments')->where($wherec)->select();
            $this->assign('minfo', json_encode($minfo,true));
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
        $data=input('get.');
        $keyword = $data["keyword"];
        $pageIndex =$data['pageIndex']?$data['pageIndex']-1:0;
        $pageSize =10;
        $page = $pageIndex*$pageSize;
        if($keyword) {
            $where["use_nickname"] = array("like","%".$keyword."%");
        }
        $list=db('org_dynamic')
            ->join('tes_user_info','use_id = ody_userid','left')
            ->join('tes_org_info','org_id = ody_orgid','left')
            ->limit($page,$pageSize)->where($where)->select();
        $status = array(1=>"未审核",2=>"未通过",3=>"已通过");
        foreach($list as $k => $v) {
            $list[$k]["oap_status"] = $status[$v['oap_status']];
        }

        $count=db('org_dynamic')
            ->join('tes_user_info','use_id = ody_userid','left')
            ->join('tes_org_info','org_id = ody_orgid','left')
            ->where($where)->count();
        $pagecount = ceil($count/$pageSize);
        $this->assign('keyword',$keyword);
        $this->assign('pageIndex',$pageIndex+1);
        $this->assign('count',$pagecount);
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
//        // 关联机构
//        $list1=db('system_infor')
//            ->join('tes_org_info','org_id = inf_jump_value','left')
//            ->where(array("inf_type"=>array("in","0,2")))
//            ->select();
//        // 关联评论
//        $list2=db('system_infor')
//            ->join('tes_org_comment','oco_id = inf_jump_value','left')
//            ->where(array("inf_type"=>1))
//            ->select();
//        $list = db()->query("select * from((select inf_id,inf_title,inf_content,inf_time,inf_type,inf_status,org_name as inf_jump_value from tes_system_infor as infor1 left join tes_org_info on org_id = inf_jump_value where inf_type in (0,2)) union
//              (select inf_id,inf_title,inf_content,inf_time,inf_type,inf_status,oco_content as inf_jump_value from tes_system_infor as infor2 left join tes_org_comment on oco_id = inf_jump_value where inf_type = 1) ) as a order by a.inf_time desc");

        $data=input('get.');
        $keyword = $data["keyword"];
        $pageIndex =$data['pageIndex']?$data['pageIndex']-1:0;
        $pageSize =10;
        $page = $pageIndex*$pageSize;
        if($keyword) {
            $where["inf_title"] = array("like","%".$keyword."%");
        }
        $list=db('system_infor')->limit($page,$pageSize)->where($where)->select();

        $type = array(0=>"系统消息", 1=>"机构评论回复", 2=>"商家消息");
        $status = array(0=>"未读取", 1=>"已读", 2=>"已删除");
        foreach($list as $k => $v) {
            $list[$k]["inf_type"] = $type[$v["inf_type"]];
            $list[$k]["inf_status"] = $status[$v["inf_status"]];
        }

        $count=db('system_infor')->where($where)->count();
        $pagecount = ceil($count/$pageSize);
        $this->assign('keyword',$keyword);
        $this->assign('pageIndex',$pageIndex+1);
        $this->assign('count',$pagecount);
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
            $orgId = explode(':',$data['inf_jump_value']);
            $data['inf_jump_value'] =$orgId[1];
            $data['inf_type'] = 2;
            $data['inf_status'] = 0;
            $data['inf_is_jump'] = 1;
            $data['inf_time'] = date("Y-m-d H:i:s",time());
            db('system_infor')->insert($data);
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
        $push_content=db('system_infor');
        if(request()->isPost()){
            $data=input('post.');
            $orgId = explode(':',$data['inf_jump_value']);
            $data['inf_jump_value'] =$orgId[1];
            $data['inf_status'] = 0;
            $where['inf_id'] = input('post.inf_id');
            $push_content->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('news');
            return $result;
        }else{
            $inf_id = input('inf_id');
            $newslist = $push_content->where(array("inf_id"=>$inf_id))->find();
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
        $inf_id=input('inf_id');
        if (empty($inf_id)){
            $result['status'] = 0;
            $result['info'] = 'ID不存在!';
            $result['url'] = url('news');
            exit;
        }
        db('system_infor')->where('inf_id='.$inf_id)->delete();
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
        $list=db('org_lesson')
            ->join("tes_org_info","org_id = tol_orgid","left")
            ->limit($page,$pageSize)->where($where)->select();

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

    /**
     * 课程预约管理
     * @date 2018-03-05
     * @author harry.lv
     */
    public function lessonOrder() {
        $data=input('get.');
        $keyword = $data["keyword"];
        $pageIndex =$data['pageIndex']?$data['pageIndex']-1:0;
        $pageSize =5;
        $page = $pageIndex*$pageSize;
        $tol_id = input('tol_id');
        $where["tol_id"] = $tol_id ? $tol_id : 0;
        if($keyword) {
            $where["tol_lesson_name"] = array("like","%".$keyword."%");
        }
        $list=db('lesson_order')
            ->field("use_nickname,tol_lesson_name,tlo_status,tlo_create_time")
            ->join("tes_org_lesson","tlo_lesson_id = tol_id","left")
            ->join("tes_user_info","tlo_uid = use_id","left")
            ->order("tlo_create_time desc")
            ->limit($page,$pageSize)->where($where)->select();
        $status = array("预约已取消","预约成功");
        foreach($list as $k => $v) {
            $list[$k]["tlo_status"] = $status[$v["tlo_status"]];
        }
        $count=db('lesson_order')
            ->join("tes_org_lesson","tlo_lesson_id = tol_id")
            ->join("tes_user_info","tlo_uid = use_id")->where($where)->count();
        $pagecount = ceil($count/$pageSize);
        $this->assign('keyword',$keyword);
        $this->assign('pageIndex',$pageIndex+1);
        $this->assign('count',$pagecount);
        $this->assign('list',$list);
        return $this->fetch();
    }

}