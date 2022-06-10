<?php
namespace app\common\addons;
use app\common\addons\Addon;
use think\Db;
use think\facade\Config;
use think\facade\Env;

use think\facade\Cache;
use net\Http;

class HomeAddon extends Addon{

    protected function initialize(){
        parent::initialize();
        if(!Config::get('web.close')){
            $this->error(Config::get('web.close_tip'));
        }
        if(!defined('UID')){
            define('UID',is_login());
        }
        if(checkDomain($this->request->domain(),Config::get('web.wap_url')) && $this->mold!='wap'){
            $this->mold='wap';
        }
        if(UID){
            model('user/user')->set_recommend();
            $this->assign('user',model('user/user')->get_info());
        }
    }


}