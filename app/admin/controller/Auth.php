<?php
namespace app\admin\controller;
use think\Db;
use clt\Leftnav;
class Auth extends Common
{
    //管理员列表
    public function adminList(){
        $val=input('val');
        $url['val'] = $val;
        $this->assign('testval',$val);
        $map='';
        if($val){
            $map['a.username|a.email|a.tel']= array('like',"%".$val."%");
        }
        if (session('adm_id')!=1){
            $map['a.adm_id']=session('adm_id');
        }

        $adminList=Db::table('tes_admin')->alias('a')
            ->join('tes_auth_group','adm_groupid = agr_id','left')
            ->field('a.*,agr_title')
            ->where($map)
            ->paginate(config('pageSize'));
        $adminList->appends($url);
        // 模板变量赋值
        $page = $adminList->render();
        $this->assign('page', $page);
        $this->assign('adminList',$adminList);
        return $this->fetch();
    }

    public function adminAdd(){
        if(request()->isPost()){
            $admin = db('admin');
            $data = input('post.');

            $check_user = $admin->where(array('adm_username'=>$data['adm_username']))->find();
            if ($check_user) {
                $result['status'] = 0;
                $result['info'] = '用户已存在，请重新输入用户名!';
                return $result;
            }
            $data['adm_pwd'] = input('post.adm_pwd', '', 'md5');
            $groupId = explode(':',$data['adm_groupid']);
            $data['adm_groupid'] =$groupId[1];
            $orgId = explode(':',$data['adm_orgid']);
            $data['adm_orgid'] =$orgId[1];
            $data['adm_addtime'] = date("Y-m-d H:i:s",time());
            $data['adm_ip'] = request()->ip();
            $admin->insert($data);
            $result['code'] = 1;
            $result['msg'] = '管理员添加成功!';
            $result['url'] = url('adminList');
            return $result;
        }else{
            $auth_group=db('auth_group')->select();
            $this->assign('authGroup',json_encode($auth_group,true));
            $orginfo=db('org_info')->where(array("org_status"=>1))->select();
            $this->assign('orginfo',json_encode($orginfo,true));
            $this->assign('title',lang('add').lang('admin'));
            $this->assign('info','null');
            return $this->fetch('adminForm');
        }
    }
    //删除管理员
    public function adminDel(){
        $adm_id=input('get.adm_id');
        if (session('adm_id')==1){
            if (empty($adm_id)){
                $result['status'] = 0;
                $result['info'] = '用户ID不存在!';
                $result['url'] = url('adminList');
                exit;
            }
            db('admin')->where('adm_id='.$adm_id)->delete();
            $this->redirect('adminList');
        }
    }
    //修改管理员状态
    public function adminState(){
        $id=input('post.adm_id');
        if (empty($id)){
            $result['status'] = 0;
            $result['info'] = '用户ID不存在!';
            $result['url'] = url('adminList');
            exit;
        }
        $status=db('admin')->where('adm_id='.$id)->value('adm_isopen');//判断当前状态情况
        if($status==1){
            $data['adm_isopen'] = 0;
            db('admin')->where('adm_id='.$id)->update($data);
            $result['status'] = 1;
            $result['info'] = lang("disabled");
        }else{
            $data['adm_isopen'] = 1;
            db('admin')->where('adm_id='.$id)->update($data);
            $result['status'] = 1;
            $result['info'] = lang("enabled");
        }
        return $result;
    }
    //更新管理员信息
    public function adminEdit(){
        if(request()->isPost()){
            $admin=db('admin');
            $data = input('post.');
            $pwd=input('post.adm_pwd');
            $map['adm_id'] = array('neq',input('post.adm_id'));
            $where['adm_id'] = input('post.adm_id');
            if(input('post.adm_username')){
                $map['adm_username'] = input('post.adm_username');
                $check_user = $admin->where($map)->find();
                if ($check_user) {
                    $result['status'] = 0;
                    $result['info'] = '用户已存在，请重新输入用户名!';
                    exit;
                }
            }
            if ($pwd){
                $data['adm_pwd'] = input('post.adm_pwd', '', 'md5');
            }else{
                unset($data['adm_pwd']);
            }
            $groupId = explode(':',$data['adm_groupid']);
            $data['adm_groupid'] =$groupId[1];
            $orgId = explode(':',$data['adm_orgid']);
            $data['adm_orgid'] =$orgId[1];
            $admin->where($where)->update($data);
            $result['code'] = 1;
            $result['msg'] = '管理员修改成功!';
            $result['url'] = url('adminList');
            return $result;
        }else{
            $auth_group = db('auth_group')->select();
            $info = db('admin')->where('adm_id='.input('adm_id'))->find();
            $this->assign('info', json_encode($info,true));
            $orginfo=db('org_info')->where(array("org_status"=>1))->select();
            $this->assign('orginfo',json_encode($orginfo,true));
            $this->assign('authGroup', json_encode($auth_group,true));
            $this->assign('title',lang('edit').lang('admin'));
            return $this->fetch('adminForm');
        }

    }
    /*-----------------------用户组管理----------------------*/
    //用户组管理
    public function adminGroup(){
        $list=db('auth_group')->select();
        $this->assign('list',$list);
        return $this->fetch();
    }
    //删除管理员分组
    public function groupDel(){
        db('auth_group')->where('agr_id='.input('agr_id'))->delete();
        $this->redirect('adminGroup');
    }
    //修改分组状态
    public function groupState(){
        $map['agr_id']=input('post.agr_id');
        $status=db('auth_group')->where($map)->value('agr_status');//判断当前状态情况
        if($status==1){
            db('auth_group')->where($map)->setField('agr_status',0);
            $result['info'] = '状态禁止';
        }else{
            db('auth_group')->where($map)->setField('agr_status',1);
            $result['info'] = '状态开启';
        }
        $result['status'] = 1;
        return $result;
    }
    //添加分组
    public function groupAdd(){
        if(request()->isPost()){
            $auth_group=db('auth_group');
            $data=input('post.');
            $data['agr_addtime']=date("Y-m-d H:i:s",time());
            $auth_group->insert($data);
            $result['msg'] = '用户组添加成功!';
            $result['url'] = url('adminGroup');
            $result['code'] = 1;
            return $result;
        }else{
            $this->assign('title','添加用户组');
            $this->assign('info','null');
            return $this->fetch('groupForm');
        }
    }
    //修改分组
    public function groupEdit(){
        if(request()->isPost()) {
            $auth_group=db('auth_group');
            $data=input('post.');
            $map['agr_id'] = input('post.agr_id');
            $auth_group->where($map)->update($data);
            $result['msg'] = '用户组修改成功!';
            $result['url'] = url('adminGroup');
            $result['code'] = 1;
            return $result;
        }else{
            $id = input('agr_id');
            $info = db('auth_group')->where(array('agr_id' => $id))->find();
            $this->assign('info', json_encode($info,true));
            $this->assign('title','编辑用户组');
            return $this->fetch('groupForm');
        }
    }
    //分组配置规则
    public function groupAccess(){
        $nav = new Leftnav();
        $admin_rule=db('auth_rule')->field('aru_id,aru_pid,aru_title')->order('aru_sort asc')->select();
        $rules = db('auth_group')->where('agr_id',input('agr_id'))->value('agr_rules');
        $arr = $nav->auth($admin_rule,$pid=0,$rules);
        $arr[] = array(
            "aru_id"=>0,
            "aru_pid"=>0,
            "aru_title"=>"全部",
            "open"=>true
        );
        $this->assign('data',json_encode($arr,true));
        return $this->fetch();
    }
    public function groupSetaccess(){
        $authGroup = db('auth_group');
        $input = input('post.agr_rules');
        if(empty($input)){
            return array('msg'=>'请选择权限!','code'=>0);
        }
        $data['agr_rules'] = input('post.agr_rules');
        $map['agr_id'] = input('post.agr_id');
        if($authGroup->where($map)->update($data)!==false){
            return array('msg'=>'权限配置成功!','url'=>url('adminGroup'),'code'=>1);
        }else{
            return array('msg'=>'保存错误','code'=>0);
        }
    }

    /********************************权限管理*******************************/
    public function adminRule(){
        $nav = new Leftnav();
        $admin_rule=db('auth_rule')->order('aru_sort asc')->select();
        $arr = $nav->menu($admin_rule);
        $this->assign('admin_rule',$arr);//权限列表
        return $this->fetch();
    }
    public function ruleAdd(){
        if(request()->isPost()){
            $authRule=db('auth_rule');
            $data = input('post.');
            $data['aru_addtime'] = date("Y-m-d H:i:s",time());
            $authRule->insert($data);
            $result['msg'] = '权限添加成功!';
            $result['url'] = url('adminRule');
            $result['code'] = 1;
            return $result;
        }else{
            $nav = new Leftnav();
            $admin_rule=db('auth_rule')->field('aru_id,aru_title,aru_pid')->order('aru_sort asc')->select();
            $arr = $nav->menu($admin_rule);
            $this->assign('admin_rule',$arr);//权限列表
            return $this->fetch();
        }
    }
    public function ruleOrder(){
        $auth_rule=db('auth_rule');
        foreach ($_POST as $id => $sort){
            $auth_rule->where('aru_id',$id)->setField('aru_sort',$sort);
        }
        $result['msg'] = '排序更新成功!';
        $result['url'] = url('adminRule');
        $result['code'] = 1;
        return $result;
    }
    public function ruleState(){
        $id=input('post.aru_id');
        $statusone=db('auth_rule')->where(array('aru_id'=>$id))->value('aru_status');//判断当前状态情况
        if($statusone==1){
            $statedata = array('aru_status'=>0);
            db('auth_rule')->where(array('aru_id'=>$id))->setField($statedata);
            $result['info'] = '状态禁止';
            $result['status'] = 1;
        }else{
            $statedata = array('aru_status'=>1);
            db('auth_rule')->where(array('aru_id'=>$id))->setField($statedata);
            $result['info'] = '状态开启';
            $result['status'] = 1;
        }
        return $result;

    }
    public function ruleTz(){
        $id=input('post.id');
        $statusone=db('auth_rule')->where(array('id'=>$id))->value('authopen');//判断当前状态情况
        if($statusone==1){
            $statedata = array('authopen'=>0);
            db('auth_rule')->where(array('id'=>$id))->setField($statedata);
            $result['info'] = '需要验证';
            $result['status'] = 1;
        }else{
            $statedata = array('authopen'=>1);
            db('auth_rule')->where(array('id'=>$id))->setField($statedata);
            $result['info'] = '无需验证';
            $result['status'] = 1;
        }
        return $result;
    }

    public function ruleDel(){
        db('auth_rule')->where(array('aru_id'=>input('aru_id')))->delete();
        $this->redirect('adminRule');
    }

    public function ruleEdit(){
        $table = db('auth_rule');
        if(request()->isPost()) {
            $datas = input('post.');
            if($table->update($datas)!==false) {
                return json(['code' => 1, 'msg' => '保存成功!', 'url' => url('adminRule')]);
            } else {
                return json(array('code' => 0, 'msg' =>'保存失败！'));
            }
        }else{
            $admin_rule=$table->field('aru_id,aru_href,aru_title,aru_icon,aru_sort,aru_status')->where(array('aru_id'=>input('aru_id')))->find();
            $this->assign('rule',json_encode($admin_rule,true));
            return $this->fetch();
        }
    }
}