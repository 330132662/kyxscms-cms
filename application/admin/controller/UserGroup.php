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

class UserGroup extends Base
{

    public function index(){
        $UserGroup=model('UserGroup');
        $list = $UserGroup->lists();
        $this->assign('list', $list);
        $this->assign('meta_title','用户组列表');
        return $this->fetch();
    }
	
	public function edit($id){
		$UserGroup=model('UserGroup');
		if($this->request->isPost()){
			$res = $UserGroup->edit();
			if($res  !== false){
                return $this->success('用户组修改成功！',url('index'));
            } else {
                $this->error($UserGroup->getError());
            }
		}else{
			$info=$UserGroup->info($id);
            $this->assign('info',$info);
			$this->assign('meta_title','修改用户组');
			return $this->fetch();
		}
	}

	public function add(){
		$UserGroup=model('UserGroup');
		if($this->request->isPost()){
			$res = $UserGroup->edit();
			if($res  !== false){
                return $this->success('用户组添加！',url('index'));
            } else {
                $this->error($UserGroup->getError());
            }
		}else{
			$this->assign('meta_title','添加用户组');
			return $this->fetch('edit');
		}
	}

	public function del(){
        $id = array_unique((array)$this->request->param('id'));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $UserGroup=model('UserGroup');
        $res = $UserGroup->del($id);
        if($res !== false){
            return $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    public function status(){
        $id = $this->request->param('id');
        $UserGroup=model('UserGroup');
        $info = $UserGroup->info($id);
        if($info['status']==1){
            return $this->forbid('UserGroup');
        }else{
            return $this->resume('UserGroup');
        }
    }
}