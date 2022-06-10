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

class User extends Base
{

    public function index(){
        $User=model('user');
        $list = $User->lists();
        $this->assign('list', $list);
        $this->assign('meta_title','用户列表');
        return $this->fetch();
    }
	
	public function edit($id){
		$User=model('user');
		if($this->request->isPost()){
            $data=$this->request->post();
            unset($data['password']);
			$res = $User->edit($data);
			if($res  !== false){
                return $this->success('用户修改成功！',url('index'));
            } else {
                $this->error($User->getError());
            }
		}else{
			$info=$User->info($id);
            $this->assign('info',$info);
			$this->assign('meta_title','修改用户');
			return $this->fetch();
		}
	}

    public function password($id){
        $User=model('user');
        if($this->request->isPost()){
            $data=$this->request->post();
            $res = $User->edit($data);
            if($res  !== false){
                return $this->success('用户密码修改成功！',url('index'));
            } else {
                $this->error($User->getError());
            }
        }
    }

	public function add(){
		$User=model('user');
		if($this->request->isPost()){
            $data=$this->request->post();
			$res = $User->edit($data);
			if($res  !== false){
                return $this->success('用户添加！',url('index'));
            } else {
                $this->error($User->getError());
            }
		}else{
			$this->assign('meta_title','添加用户');
			return $this->fetch('edit');
		}
	}

	public function del(){
        $User=model('user');
        $id = array_unique((array)$this->request->param('id'));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $res=$User->del($id);
        if($res){
            return $this->success('删除成功');
        } else {
            $this->error($User->getError());
        }
    }

    public function status(){
        $id = $this->request->param('id');
        $User=model('user');
        $info = $User->info($id);
        if($info['status']==1){
            return $this->forbid('User');
        }else{
            return $this->resume('User');
        }
    }
}