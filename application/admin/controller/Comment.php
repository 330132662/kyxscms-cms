<?php
namespace app\admin\controller;
use think\Db;

class Comment extends Base
{

    public function index($type,$id=null){
        $list = model('comment')->lists($type,$id);
        $page = $list->render();
        $list = $list->toArray();
        $list = model('comment')->get_tree($list['data']);
        $this->assign('tree', $list);
        $this->assign('page', $page);
        $this->assign('meta_title','评论列表');
        return $this->fetch();
    }

    public function tree($tree = null){
        $this->assign('tree', $tree);
        return $this->view->fetch();
    }
	
	public function edit($id){
		$Comment=model('comment');
		if($this->request->isPost()){
            $data=$this->request->post();
			$res = $Comment->edit($data);
			if($res  !== false){
                return $this->success('评论修改成功！',url('index'));
            } else {
                $this->error($Comment->getError());
            }
		}else{
			$info=$Comment->info($id);
            $this->assign('info',$info);
			$this->assign('meta_title','修改评论');
			return $this->fetch();
		}
	}

	public function del(){
        $Comment=model('comment');
        $id = array_unique((array)$this->request->param('id'));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $res=$Comment->del($id);
        if($res){
            return $this->success('删除成功');
        } else {
            $this->error($Comment->getError());
        }
    }
}