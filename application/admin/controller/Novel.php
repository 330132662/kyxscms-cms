<?php
namespace app\admin\controller;

class Novel extends Base
{

    public function index(){
        $Novel=model('novel');
        $map[]  = ['sole','>',1];
        $list = $Novel->lists($map);
        $this->assign('list', $list);
        $this->assign('category', get_tree(0));
        $this->assign('meta_title','小说列表');
        return $this->fetch();
    }

    public function open(){
        $Novel=model('novel');
        $map[]  = ['sole','>',1];
        $list = $Novel->lists($map);
        $this->assign('list', $list);
        $this->assign('category', get_tree(0));
        $this->assign('meta_title','小说选择列表');
        return $this->fetch();
    }

    public function position($id){
        $Novel=model('novel');
        if($this->request->isPost()){
            $data = $this->request->post();
            $res = $Novel->edit($data,$this->request->action());
            if($res  !== false){
                $this->success('小说推荐修改成功！',url('index'));
            } else {
                $this->error($Novel->getError());
            }
        }else{
            $info=$Novel->info($id);
            $this->assign('info',$info);
            $this->assign('meta_title','推荐');
            return $this->fetch();
        }
    }

    public function category(){
        if($this->request->isPost()){
            $data = $this->request->post();
            $Novel=model('novel');
            $res = $Novel->edit_field($data);
            if($res  !== false){
                $this->success('小说分类修改成功！',url('index'));
            } else {
                $this->error($Novel->getError());
            }
        }else{
            $this->assign('category', get_tree(0));
            return $this->fetch();
        }
    }
	
	public function edit($id){
		$Novel=model('novel');
		if($this->request->isPost()){
            $data = $this->request->post();
			$res = $Novel->edit($data,$this->request->action());
			if($res  !== false){
                $this->success('小说修改成功！',url('index'));
            } else {
                $this->error($Novel->getError());
            }
		}else{
			$info=$Novel->info($id);
            $this->assign('info',$info);
            $this->assign('category', get_tree(0));
			$this->assign('meta_title','修改小说');
			return $this->fetch();
		}
	}

	public function add($pid = 0){
		$Novel=model('novel');
		if($this->request->isPost()){
            $data = $this->request->post();
			$res = $Novel->edit($data,$this->request->action());
			if($res  !== false){
                $this->success('小说添加！',url('index'));
            } else {
                $this->error($Novel->getError());
            }
		}else{
            $this->assign('category', get_tree(0));
			$this->assign('meta_title','添加小说');
			return $this->fetch('edit');
		}
	}

	public function del(){
        $id = array_unique((array)$this->request->param('id'));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $Novel=model('novel');
        $res = $Novel->del($id);
        if($res  !== false){
            $this->success('删除成功');
        } else {
            $this->error($Novel->getError());
        }
    }

    public function status(){
        $id = $this->request->param('id');
        $Novel=model('novel');
        $info = $Novel->info($id);
        if($info['status']==1){
            return $this->forbid('Novel');
        }else{
            return $this->resume('Novel');
        }
    }
}