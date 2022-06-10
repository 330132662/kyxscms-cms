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
use think\facade\Cookie;

class Lists extends Base{

	public function index(){
		Cookie::set('__forward__',$this->request->url());
		$cid=$this->request->param('id');
		$info=model("common/api")->get_category($cid);
		if(empty($info)){
			$this->error('未找到该栏目！','home/index/index');
		}
		$this->view->list_title=empty($info["meta_title"]) ? config("web.meta_title") : $info["meta_title"];
		$this->view->list_keywords=empty($info["meta_keywords"]) ? config("web.meta_keyword") : $info["meta_keywords"];
		$this->view->list_description=empty($info["meta_description"]) ? config("web.meta_description") : $info["meta_description"];
		if($info['type']===2){
			$tpl=model('common/api')->get_tpl($info['id'],'template_detail');
		}else{
			$tpl=model('common/api')->get_tpl($info['id'],'template_index');
		}
		if(!$tpl){
			$error = model('common/api')->getError();
        	$this->error(empty($error) ? '未知错误！' : $error);
		}
		$this->assign(['cid'=>$info['id'],'pid'=>$info['pid'],'title'=>$info['title'],'icon'=>$info['icon'],'pos'=>2]);
		return $this->fetch($this->home_tplpath.$tpl);
	}

	public function lists(){
		Cookie::set('__forward__',$this->request->url());
		$cid=$this->request->param('id');
		$size=$this->request->param('size');
		$serialize=$this->request->param('serialize');
		$update=$this->request->param('update');
		$tag=$this->request->param('tag');
		$info=model("common/api")->get_category($cid);
		$this->view->list_title=empty($info["meta_title"]) ? config("web.meta_title") : $info["meta_title"];
		$this->view->list_keywords=empty($info["meta_keywords"]) ? config("web.meta_keyword") : $info["meta_keywords"];
		$this->view->list_description=empty($info["meta_description"]) ? config("web.meta_description") : $info["meta_description"];
		$tpl=model('common/api')->get_tpl($cid,'template_filter');
		if(!$tpl){
			$tpl='lists.html';
		}
		$this->assign(['cid'=>$info['id'],'pid'=>$info['pid'],'title'=>$info['title'],'icon'=>$info['icon'],'size'=>$size,'serialize'=>$serialize,'update'=>$update,'tag'=>$tag,'pos'=>1]);
		return $this->fetch($this->home_tplpath.$tpl);
	}
}