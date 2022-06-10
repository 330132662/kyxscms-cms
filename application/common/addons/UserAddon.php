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

namespace app\common\addons;
use app\common\addons\Addon;
use think\Db;
use think\facade\Config;
use think\facade\Cache;

class UserAddon extends Addon{

	protected function initialize(){
        parent::initialize();
        if(!Config::get('web.close')){
            $this->error(Config::get('web.close_tip'));
        }
        if(checkDomain($this->request->domain(),Config::get('web.wap_url')) && $this->mold!='wap'){
            $this->mold='wap';
        }
        if(!defined('UID')){
            define('UID',is_login());
        }
        if(empty(UID)){
            Cookie('__forward__',$this->request->url());
            if($this->request->isAjax()){
                $this->error('请先登录！',url('user/user/login'));
            }else{
                $this->redirect('user/user/login');
            }
        }else{
            model('user/user')->set_recommend();
            $this->assign('user',model('user/user')->get_info());
        }
        $this->assign('user_menu',$this->get_menu());
    }

    public function get_menu(){
        $user_menu=Cache::get('user_menu');
        if(!$user_menu){
            $where=['pid'=>0,'hide'=>0];
            $user_menu = Db::name('user_menu')->where($where)->order('sort asc')->select();
            foreach ($user_menu as $key => $value) {
                $menu= Db::name('user_menu')->where('pid',$value['id'])->order('sort asc')->select();
                $user_menu[$key]['child']=$menu;
            }
            Cache::set('user_menu',$user_menu);
        }
        return $user_menu;
    }


}