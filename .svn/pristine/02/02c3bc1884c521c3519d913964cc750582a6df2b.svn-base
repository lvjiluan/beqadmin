<?php
namespace app\admin\model;
use think\Model;
class Admin extends Model
{
	public function login($data){
		$user=db('admin')->where('adm_username',$data['adm_username'])->find();
		if($user){
			if($user['adm_pwd'] == md5($data['adm_pwd'])){
				session('adm_username',$user['adm_username']);
				session('adm_id',$user['adm_id']);
                session('adm_orgid',$user['adm_orgid']);
				return 1; //信息正确
			}else{
				return -1; //密码错误
			}
		}else{
			return -1; //用户不存在
		}
	}

}
