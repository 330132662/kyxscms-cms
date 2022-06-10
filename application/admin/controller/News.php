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

class News extends Base
{

    public function index(){
        $News=model('news');
        $list = $News->lists();
        $this->assign('list', $list);
        $this->assign('category', get_tree(1));
        $this->assign('meta_title','文章列表');
        return $this->fetch();
    }

    public function open(){
        $News=model('News');
        $list = $News->lists();
        $this->assign('list', $list);
        $this->assign('category', get_tree(1));
        $this->assign('meta_title','文章选择列表');
        return $this->fetch();
    }

    public function position($id){
        $News=model('news');
        if($this->request->isPost()){
            $data = $this->request->post();
            $res = $News->edit($data,$this->request->action());
            if($res  !== false){
                $this->success('小说推荐修改成功！',url('index'));
            } else {
                $this->error($News->getError());
            }
        }else{
            $info=$News->info($id);
            $this->assign('info',$info);
            $this->assign('meta_title','推荐');
            return $this->fetch();
        }
    }

    public function category(){
        if($this->request->isPost()){
            $data = $this->request->post();
            $News=model('news');
            $res = $News->edit_field($data);
            if($res  !== false){
                $this->success('小说分类修改成功！',url('index'));
            } else {
                $this->error($News->getError());
            }
        }else{
            $this->assign('category', get_tree(1));
            return $this->fetch();
        }
    }
	
	public function edit($id){
		$News=model('news');
		if($this->request->isPost()){
            $data = $this->request->post();
			$res = $News->edit($data,$this->request->action());
			if($res  !== false){
                $this->success('文章修改成功！',url('index'));
            } else {
                $this->error($News->getError());
            }
		}else{
			$info=$News->info($id);
            $this->assign('info',$info);
            $this->assign('category', get_tree(1));
			$this->assign('meta_title','修改文章');
			return $this->fetch();
		}
	}

	public function add(){
		$News=model('news');
		if($this->request->isPost()){
            $data = $this->request->post();
			$res = $News->edit($data,$this->request->action());
			if($res  !== false){
                $this->success('文章添加！',url('index'));
            } else {
                $this->error($News->getError());
            }
		}else{
            $this->assign('category', get_tree(1));
			$this->assign('meta_title','添加文章');
			return $this->fetch('edit');
		}
	}

	public function del(){
        $id = array_unique((array)$this->request->param('id'));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $News=model('news');
        $res = $News->del($id);
        if($res  !== false){
            $this->success('删除成功');
        } else {
            $this->error($News->getError());
        }
    }

    public function status(){
        $id = $this->request->param('id');
        $News=model('news');
        $info = $News->info($id);
        if($info['status']==1){
            return $this->forbid('News');
        }else{
            return $this->resume('News');
        }
    }
}