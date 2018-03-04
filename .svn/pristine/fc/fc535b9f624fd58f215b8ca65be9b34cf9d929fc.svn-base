<?php
namespace app\admin\model;

use think\Model;

class Users extends Model
{
	protected $name = 'users';
	// birthday修改器
	protected function setpwdAttr($value){
			return md5($value);
	}
}