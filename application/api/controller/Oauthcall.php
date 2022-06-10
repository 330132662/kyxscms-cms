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

namespace app\api\controller;
use think\Controller;
use think\Db;
use think\facade\Config;
use think\facade\Cookie;
use org\Oauth;
use net\Http;

class Oauthcall extends Controller{
	public function index(){
		if($this->request->isGet()){
			if($token=$this->request->get('access_token')){
				$expires=$this->request->get('expires_in');
				$auth = new Oauth();
				$auth->setToken($token,$expires);
				$this->redirect(Cookie::get('__forward__'),302);
			}
		}
	}

	public function check_order(){
		$url=Config::get('web.official_url').'/check_order/';
		$data=[];
		$data['client_id']=Config::get('web.client_id');
        $data['client_secret']=Config::get('web.client_secret');
        $data['addons']=Db::name('addons')->column('name');
        $data['template']=Db::name('template')->column('name');
        $content=Http::doPost($url,$data,60);
        $content=json_decode($content,true);
        if(is_array($content)){
        	foreach ($content as $key => $value) {
	        	if(!empty($value) && is_array($value)){
	        		foreach ($value as $k => $v) {
	        			Db::name($key)->where(['name'=>$v])->delete();
	        			if($key=='template'){
	        				del_dir_file('./'.$key.'/home/'.$v,true);
	        			}else{
	        				del_dir_file('./'.$key.'/'.$v,true);
	        			}
	        		}
	        	}else{
	        		Db::name('crontab')->where(['class_name'=>'api/Oauthcall'])->update(['run_time'=>time()+2592000]);
	        	}
	        }
        }
        
	}
}