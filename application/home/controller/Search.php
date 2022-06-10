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
use think\facade\Config;
use think\facade\Session;
use think\facade\Cookie;

class Search extends Base{

	public function index(){
		if(Config::get('web.search_on')!=1){
			$this->error('已经关闭搜索功能!');
		}
		Cookie::set('__forward__',$this->request->url());
		$search_time = Session::get('search_time');
    	$res = time() - intval($search_time);
    	session('search_time',time());
    	if($res<Config::get('web.search_timespan')){
    		$this->error('请不要频繁操作，搜索时间间隔为'.Config::get('web.search_timespan').'秒!');
    	}
		$keyword=$this->request->param('keyword');
		$this->assign(['keyword'=>$keyword]);
		return $this->fetch($this->home_tplpath."/search.html");
	}
}