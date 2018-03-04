<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
use app\admin\model\Users as UsersModel;
class Users extends Common{
    //会员列表
    public function index(){
        if(request()->isPost()){
            $key=input('post.key');
            $page =input('pageIndex');
            $pageSize =input('pageSize');
            $list=db('users')->alias('u')
                ->join(config('database.prefix').'user_level ul','u.level = ul.level_id','left')
                ->field('u.*,ul.level_name')
                ->where('u.email|u.mobile|u.nickname','like',"%".$key."%")
                ->order('u.user_id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            $rsult['code'] = 0;
            $rsult['msg'] = "获取成功";
            $rsult['list'] = $list['data'];
            $rsult['count'] = $list['total'];
            $rsult['rel'] = 1;
            echo json_encode($rsult);
            exit;
        }
        return $this->fetch();
    }
    public function usersState(){
        $id=input('post.id');
        $status=db('users')->where(array('user_id'=>$id))->value('is_lock');//判断当前状态情况
        if($status==1){
            $data['is_lock'] = 0;
            db('users')->where(array('user_id'=>$id))->setField($data);
            $result['info'] = '状态开启';
            $result['status'] = 1;
        }else{
            $data['is_lock'] = 1;
            db('users')->where(array('user_id'=>$id))->setField($data);
            $result['info'] = '状态禁止';
            $result['status'] = 1;
        }
        return $result;
    }

    public function edit($user_id=''){
        if(request()->isPost()){
            $user = db('users');
            $data = input('post.');
            $level =explode(':',$data['level']);
            $data['level'] = $level[1];
            $province =explode(':',$data['province']);
            $data['province'] = $province[1];
            $city =explode(':',$data['city']);
            $data['city'] = $city[1];
            $district =explode(':',$data['district']);
            $data['district'] = $district[1];
            if ($user->update($data)!==false) {
                $result['msg'] = '会员修改成功!';
                $result['url'] = url('index');
                $result['code'] = 1;
            } else {
                $result['msg'] = '会员修改失败!';
                $result['code'] = 0;
            }
            return $result;
        }else{
            $province = db('Region')->where ( array('pid'=>1) )->select ();
            $user_level=db('user_level')->order('sort')->select();
            $info = UsersModel::get($user_id);
            $this->assign('info',json_encode($info,true));
            $this->assign('title',lang('edit').lang('user'));
            $this->assign('province',json_encode($province,true));
            $this->assign('user_level',json_encode($user_level,true));

            $city = db('Region')->where ( array('pid'=>$info['province']) )->select ();
            $this->assign('city',json_encode($city,true));
            $district = db('Region')->where ( array('pid'=>$info['city']) )->select ();
            $this->assign('district',json_encode($district,true));
            return $this->fetch();
        }
    }


    public function getRegion(){
        $Region=db("region");
        $pid = input("pid");
        $arr = explode(':',$pid);
        $map['pid']=$arr[1];
        $list=$Region->where($map)->select();
        return $list;
    }

    public function usersDel($user_id=''){
        UsersModel::destroy($user_id);
        $this->redirect('index');
    }
    public function delall(){
        $map['user_id'] =array('in',input('param.ids'));
        db('users')->where($map)->delete();
        $result['msg'] = '删除成功！';
        $result['url'] = url('index');
        return $result;
    }

    /***********************************会员组***********************************/
    public function userGroup(){
        $userLevel=db('user_level');
        $list=$userLevel->order('sort')->select();
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function groupAdd(){
        if(request()->isPost()){
            $data = input('post.');
            $data['open'] = input('post.open') ? input('post.open') : 0;
            db('user_level')->insert($data);
            $result['msg'] = '会员组添加成功!';
            $result['url'] = url('userGroup');
            $result['code'] = 1;
            return $result;
        }else{
            $this->assign('title',lang('add')."会员组");
            $this->assign('info','null');
            return $this->fetch('groupForm');
        }
    }
    public function groupEdit(){
        if(request()->isPost()) {
            $data = input('post.');
            db('user_level')->update($data);
            $result['msg'] = '会员组修改成功!';
            $result['url'] = url('userGroup');
            $result['code'] = 1;
            return $result;
        }else{
            $map['level_id'] = input('param.level_id');
            $info = db('user_level')->where($map)->find();
            $this->assign('title',lang('edit')."会员组");
            $this->assign('info',json_encode($info,true));
            return $this->fetch('groupForm');
        }
    }
    public function groupDel(){
        $level_id=input('level_id');
        if (empty($level_id)){
            $this->error('会员组ID不存在',url('userGroup'),0);
        }
        db('user_level')->where(array('level_id'=>$level_id))->delete();
        $this->redirect('userGroup');
    }
    public function groupOrder(){
        $userLevel=db('user_level');
        $data = input('post.');
        foreach ($data as $id => $sort){
            $userLevel->where(array('level_id' => $id ))->setField('sort' , $sort);
        }
        $result['msg'] = '排序更新成功!';
        $result['url'] = url('userGroup');
        $result['code'] = 1;
        return $result;
    }




}