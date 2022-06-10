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

class Slider extends Base
{

    public function index(){
        $list = model('Slider')->lists();
        $this->assign('list', $list);
        $this->assign('meta_title','幻灯列表');
        return $this->fetch();
    }
	
	public function edit($id){
		$Slider=model('Slider');
		if($this->request->isPost()){
			$res = $Slider->edit();
			if($res  !== false){
                $this->success('幻灯修改成功！',url('index'));
            } else {
                $this->error($Slider->getError());
            }
		}else{
			$info=$Slider->info($id);
            $this->assign('info',$info);
			$this->assign('meta_title','修改幻灯');
			return $this->fetch();
		}
	}

	public function add(){
		$Slider=model('Slider');
		if($this->request->isPost()){
			$res = $Slider->edit();
			if($res  !== false){
                $this->success('幻灯添加！',url('index'));
            } else {
                $this->error($Slider->getError());
            }
		}else{
			$this->assign('meta_title','添加幻灯');
			return $this->fetch('edit');
		}
	}

	public function del(){
        $id = array_unique((array)$this->request->param('id'));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $Slider=model('Slider');
        $res = $Slider->del($id);
        if($res !== false){
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    public function status(){
        $id = $this->request->param('id');
        $Slider=model('Slider');
        $info = $Slider->info($id);
        if($info['status']==1){
            return $this->forbid('Slider');
        }else{
            return $this->resume('Slider');
        }
    }
}