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
use app\admin\validate\Member as MemberValidate;
use think\facade\Request;
use think\Model;

class Member extends Model {
	
    protected $insert = ['status'=>1];

    protected function setPasswordAttr($value){
        return think_ucenter_md5($value);
    }

	public function info($uid){
		$member = new Member;
		$map['id'] = $uid;
		$map['status']=1;
		$info=$member->where($map)->field('id,username,last_login_time')->find();
		return $info;
	}
	
	public function reg(){
		$data=Request::post();
		$validate = new MemberValidate;
		if (!$validate->check($data)) {
            $this->error=$validate->getError();
            return false;
        }
		$member = new Member;
		$result = $member->allowField(true)->save($data);
		if(false === $result){
			$this->error=$member->getError();		
		}
		return $result;
	}
	
	public function password(){
		$data=Request::post();
		$member = new Member;
		if(!$this->verifyUser($data['id'], $data['oldpassword'])){
			$this->error = '验证出错：旧密码不正确！';
			return false;
		}
		$validate = new MemberValidate;
		if (!$validate->check($data)) {
            $this->error=$validate->getError();
            return false;
        }
        $result = $member->allowField(true)->save($data,['id'=>$data['id']]);
        if(false === $result){
		    $this->error=$member->getError();
		}
        return $result;
    }
	
	protected function verifyUser($uid, $password_in){
		$password = Member::where('id',$uid)->value('password');
		if(think_ucenter_md5($password_in) === $password){
			return true;
		}
		return false;
	}

	public function del($id){
        $map = ['id' => $id];
        $result = Member::where($map)->delete();
        if(false === $result){
            $this->error=Member::getError();
            return false;
        }else{
            return $result;
        }
    }
}