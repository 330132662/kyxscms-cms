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

namespace app\common\controller;
use think\Controller;
use think\Db;
use think\facade\Config;
use think\facade\Env;
use think\facade\Cache;

class UserBase extends Controller{
    protected $mold;
    protected $user_tplpath;

    protected function initialize(){
        if(!Config::get('web.close')){
            $this->error(Config::get('web.close_tip'));
        }
        $allowUrl = ['user/user/login',
                     'user/user/logout',
                     'user/user/reg',
                     'user/user/forgetpwd',
                     'user/user/pact',
                     'user/user/verify',
                     'user/user/check_require',
                     'user/user/check_code',
                     'user/user/email_code'
                    ];
        $visit = strtolower($this->request->module()."/".$this->request->controller()."/".$this->request->action());
        if(!defined('UID')){
            define('UID',is_login());
        }
        if(empty(UID) && !in_array($visit,$allowUrl)){
            if($this->request->isAjax()){
                $this->error('请先登录！',url('user/user/login'));
            }else{
                $this->redirect('user/user/login');
            }
        }else{
            model('user/user')->set_recommend();
            $this->assign('user',model('user')->get_info());
        }
        $this->mold=($this->request->isMobile())?'wap':'web';
        if(checkDomain($this->request->domain(),Config::get('web.wap_url')) && $this->mold!='wap'){
            $this->mold='wap';
        }
        $this->view->config(['cache_path'=>Env::get('runtime_path').'temp'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR.$this->mold.DIRECTORY_SEPARATOR]);
        $this->user_tplpath='template/user/'.$this->mold.'/';
        $this->assign('web',Config::get('web.'));
        $this->assign('mold',$this->mold);
        $this->assign('user_menu',$this->get_menu());
        $this->assign('user_tplpath','/'.$this->user_tplpath);
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