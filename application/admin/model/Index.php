<?php
// +----------------------------------------------------------------------
// | KyxsCMS [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018~2019 http://www.kyxscms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: kyxscms
// +----------------------------------------------------------------------

namespace app\admin\model;
use think\Model;
use think\Db;
use think\facade\Request;

class Index extends Model {
    
	public function login($username, $password){
		$map = [];
		$map['username'] = $username;
		$map['status'] = 1;
		$user = Db::name('member')->where($map)->find();
		if(is_array($user)){
			if(think_ucenter_md5($password) === $user['password']){
				$this->autoLogin($user);
				return $user['id'];
			} else {
				return -2;
			}
		} else {
			return -1;
		}
	}
	
    private function autoLogin($user){
        $data = [
            'id'             => $user['id'],
            'login'           => ['inc', 1],
            'last_login_time' => Request::time(),
            'last_login_ip'   => Request::ip(1)
        ];
        Db::name("member")->update($data);
        $auth = [
            'uid'             => $user['id'],
            'username'        => $user['username'],
            'last_login_time' => $user['last_login_time']
        ];
        session('admin_auth', $auth);
        session('admin_auth_sign', data_auth_sign($auth));
    }

	public function logout(){
        session('admin_auth', null);
        session('admin_auth_sign', null);
    }
}