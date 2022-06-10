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
use think\Db;
use think\facade\Request;

class ActionBeginAddons{
	// 行为扩展的执行入口必须是run
    public function run($params){
    	if((strtolower(Request::module())=='home' || strtolower(Request::module())=='user')){
            if($this->is_weixin() && empty(is_login())){
                $wechat = Db::name('Addons')->where(['status'=>1,'name'=>'Wechat'])->field('name')->find();
                if($wechat){
                    $wechat_oauth = new \addons\Wechat\controller\Oauth();
                    $wechat_oauth->index();
                }
            }
        }
    }

    private function is_weixin(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false  || strpos($_SERVER['HTTP_USER_AGENT'], 'MiniQB') !== false ) {
            return true;
        }   
        return false;
    }
}