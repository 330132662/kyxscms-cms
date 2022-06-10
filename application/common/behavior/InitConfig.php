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

namespace app\common\behavior;
use think\facade\Config;
use think\facade\Cache;

/*
 * 初始化配置数据
 */
class InitConfig
{
    public function run($params){
    	if(is_file(APP_PATH . 'install/data/install.lock')){
	    	try {
		        $config =   Cache::get('config_data');
		        if(!$config){
		            $config =  config_lists();
		            Cache::set('config_data',$config);
		        }
		        Config::set($config,'web');
	        }catch(Exception $e){}
	    }
    }
}