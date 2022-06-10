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

namespace app\admin\controller;
use think\Db;

class Member extends Base
{

    public function index(){
        $list  = Db::name('member')->paginate(config('web.list_rows'));
        $this->assign('list', $list);
        $this->assign('meta_title','管理员列表');
        return $this->fetch();
    }

    public function password(){
		 if($this->request->isPost()){
            $Member=model('Member');
			$res  =  $Member->password();
			if($res  !== false){
				$this->success('修改密码成功！',url('index'));
			}else{
				$this->error($Member->getError());
			}
		 }else{
		 	$username = Db::name('member')->where('id',$this->request->param('id'))->value('username');
			$this->assign('username', $username);
            $this->assign('meta_title','修改密码');
			return $this->fetch();
		 }
    }

    public function group(){
        $Auth=model('auth');
        if($this->request->isPost()){
            $uid = $this->request->post('uid');
            $gid = $this->request->post('group_id/a');
            if(empty($uid)){
                $this->error('参数有误');
            }
            if(is_numeric($uid)){
                if (is_administrator($uid) ){
                    $this->error('该用户为超级管理员');
                }
                if(!Db::name('Member')->where(['id'=>$uid])->find()){
                    $this->error('用户不存在');
                }
            }
            $Auth->addToGroup($uid,$gid);
            $this->success('操作成功！',url('index'));
        }else{
            $uid            =   $this->request->param('id');
            $auth_groups    =   $Auth->getGroups();
            $user_groups    =   $Auth->getUserGroup($uid);
            $ids = [];
            foreach ($user_groups as $value){
                $ids[]      =   $value['group_id'];
            }
            $this->assign('auth_groups',$auth_groups);
            $this->assign('user_groups',implode(',',$ids));
            $this->assign('meta_title','设置权限');
            return $this->fetch();
         }
    }
	
	public function add(){
		if($this->request->isPost()){
            $Member=model('Member');
			if($this->request->post("password") != $this->request->post("repassword")){
                $this->error('密码和重复密码不一致！');
            }
			if(Db::name('member')->where(['username'=>$this->request->post("username")])->value('id')){
				$this->error('用户名已被占用！');
			}
			$res = $Member->reg();
			if($res  !== false){
                $this->success('用户添加成功！',url('index'));
            } else {
                $this->error($Member->getError());
            }
		}else{
            $this->assign('meta_title','添加管理员');
			return $this->fetch();
		}
	}
	
    public function del(){
        $id = array_unique((array)$this->request->param('id'));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $Member=model('Member');
        $res = $Member->del($id);
        if($res !== false){
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
}