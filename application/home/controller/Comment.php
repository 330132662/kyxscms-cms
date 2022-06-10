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

namespace app\home\controller;
use app\common\controller\Base;
use think\Db;

class Comment extends Base
{
	public function index(){
		return $this->fetch("template/comment/comment.html");
	}

    public function lists(){
		$tree = model('common/comment')->get_tree($this->request->param('id'),$this->request->param('type'));
		$this->assign('count',Db::name('comment')->where(['mid'=>$this->request->param('id'),'pid'=>0,'status'=>1])->count());
		$this->assign('limit',$this->request->param('limit'));
        $this->assign('size',$this->request->param('size'));
        $this->assign('tree', $tree);
		$this->assign("mid",$this->request->param('id'));
        return $this->fetch("template/comment/comment.html");
    }

    /**
     * 显示分类树，仅支持内部调
     * @param  array $tree 分类树
     */
    public function tree($tree = null){
    	if(!is_array($tree)){
    		$tree=json_decode($tree,true);
    	}
        $this->assign('tree', $tree);
        return $this->fetch('template/comment/tree.html');
    }

    public function add(){
    	if(UID){
    		$data=$this->request->post();
    		$Comment=model('common/comment');
    		if($Comment->comment_add($data)){
	    		return $this->success('评论发送成功！');
	    	}else{
	    		$this->error($Comment->getError());
	    	}
    	}else{
    		$this->error('请先登录！');
    	}
    }

    public function up($id){
    	if(!cookie('comment_up_'.$id)){
    		cookie('comment_up_'.$id,true);
    		Db::name('Comment')->where(['id'=>$id])->setInc('up');
    		return $this->success('+1');
    	}else{
    		$this->error('请不要重复点赞！');
    	}
    }
}
