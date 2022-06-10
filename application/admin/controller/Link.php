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

class Link extends Base
{

    public function index(){
        $list = Db::name('Link')->order('sort asc')->paginate(config('web.list_rows'));
        $this->assign('list', $list);
        $this->assign('meta_title','友情链接列表');
        return $this->fetch();
    }
	
	public function edit($id){
		$Link=model('link');
		if($this->request->isPost()){
			$res = $Link->edit();
			if($res  !== false){
                return $this->success('友情链接修改成功！',url('index'));
            } else {
                $this->error($Link->getError());
            }
		}else{
			$info=$Link->info($id);
            $this->assign('info',$info);
			$this->assign('meta_title','修改友情链接');
			return $this->fetch();
		}
	}

	public function add(){
		$Link=model('link');
		if($this->request->isPost()){
			$res = $Link->edit();
			if($res  !== false){
                return $this->success('友情链接添加！',url('index'));
            } else {
                $this->error($Link->getError());
            }
		}else{
			$this->assign('meta_title','添加友情链接');
			return $this->fetch('edit');
		}
	}

	public function del(){
        $id = array_unique((array)$this->request->param('id'));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $Link=model('Link');
        $res = $Link->del($id);
        if($res !== false){
            return $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
}