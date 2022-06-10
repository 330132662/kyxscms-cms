<?php
namespace app\admin\controller;
use think\Db;

class Ad extends Base
{

    public function index(){
        $Ad=model('Ad');
        $list = $Ad->lists();
        $this->assign('list', $list);
        $this->assign('meta_title','广告列表');
        return $this->fetch();
    }
	
	public function edit($id){
		$Ad=model('Ad');
		if($this->request->isPost()){
            $data = $this->request->post();
			$res = $Ad->edit($data);
			if($res  !== false){
                $this->success('广告修改成功！',url('index'));
            } else {
                $this->error($Ad->getError());
            }
		}else{
			$info=$Ad->info($id);
            $this->assign('info',$info);
            $this->assign('category', get_tree(0));
			$this->assign('meta_title','修改广告');
			return $this->fetch();
		}
	}

	public function add($pid = 0){
		$Ad=model('Ad');
		if($this->request->isPost()){
            $data = $this->request->post();
			$res = $Ad->edit($data);
			if($res  !== false){
                $this->success('广告添加！',url('index'));
            } else {
                $this->error($Ad->getError());
            }
		}else{
            $this->assign('category', get_tree(0));
			$this->assign('meta_title','添加广告');
			return $this->fetch('edit');
		}
	}

	public function del(){
        $id = array_unique((array)$this->request->param('id'));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $Ad=model('Ad');
        $res = $Ad->del($id);
        if($res  !== false){
            $this->success('删除成功');
        } else {
            $this->error($Ad->getError());
        }
    }
}