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
use app\common\controller\ApiBase;
use think\facade\Config;
use think\Db;

class Common extends ApiBase{

	public function system(){
		$config=Config::get('web.');
		unset($config['version'],$config['client_id'],$config['client_secret'],$config['api_key']);
		$map[] = ['','exp',Db::raw('find_in_set("'.$this->mold.'",`mold`)')];
        $map[] = ['status','=',1];
        $addons=Db::name('addons')->field('id,name,title,version,group')->where($map)->column('group',"name");
        return json(["code"=>1,"data"=>["config"=>$config,"addons"=>$addons]]);
	}

	public function config(){
		$config=Config::get('web.');
		unset($config['version'],$config['client_id'],$config['client_secret'],$config['api_key']);
		return json(["code"=>1,"data"=>$config]);
	}

	public function addons_list(){
		$map[] = ['','exp',Db::raw('find_in_set("'.$this->mold.'",`mold`)')];
        $map[] = ['status','=',1];
        $addons=Db::name('addons')->field('id,name,title,version,group')->where($map)->select();
		return json(["code"=>1,"data"=>$addons]);
	}

	public function addons($group){
		$map[] = ['group','=',$group];
		$map[] = ['','exp',Db::raw('find_in_set("'.$this->mold.'",`mold`)')];
        $map[] = ['status','=',1];
        $addons=Db::name('addons')->where($map)->value('name');
        $addons_class = get_addon_class($addons);
        if(!class_exists($addons_class))
            return json(["code"=>0,"msg"=>'插件不存在']);
		return json(["code"=>1,"data"=>$addons]);
	}

	public function slider($limit,$type=false){
		$api=model('common/api');
		$api->api_url=true;
		$slider=$api->get_slider($limit,$type);
		return json(["code"=>1,"data"=>$slider]);
	}
}