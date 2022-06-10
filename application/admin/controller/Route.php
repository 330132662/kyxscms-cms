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

class Route extends Base
{

    public function index(){
        $list = Db::name('route')->where(['display' => 1])->paginate(config('web.list_rows'));
        $this->assign('list', $list);
        $this->assign('meta_title','路由列表');
        return $this->fetch();
    }
	
	public function edit($id){
		$Route=model('Route');
		if($this->request->isPost()){
			$res = $Route->edit();
			if($res  !== false){
                $this->success('路由修改成功！',url('index'));
            } else {
                $this->error($Route->getError());
            }
		}else{
			$info=$Route->info($id);
            $this->assign('info',$info);
			$this->assign('meta_title','修改路由');
			return $this->fetch();
		}
	}

	public function add(){
		$Route=model('Route');
		if($this->request->isPost()){
			$res = $Route->edit();
			if($res  !== false){
                $this->success('路由添加！',url('index'));
            } else {
                $this->error($Route->getError());
            }
		}else{
			$this->assign('meta_title','添加路由');
			return $this->fetch('edit');
		}
	}

	public function del(){
        $id = array_unique((array)$this->request->param('id'));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $Route=model('Route');
        $res = $Route->del($id);
        if($res !== false){
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
}