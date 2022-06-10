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
use think\facade\Config;

class ApiUserBase extends Controller{

    protected function initialize(){
        if(!Config::get('web.close')){
             $this->json_error(["code"=>0,"msg"=>Config::get('web.close_tip')]);
        }
        $allowUrl = ['api/user/login',
                     'api/user/logout',
                     'api/user/reg',
                     'api/user/forgetpwd',
                     'api/user/verify',
                     'api/user/check_require',
                     'api/user/check_code',
                     'api/user/email_code'
                    ];
        $visit = strtolower($this->request->module()."/".$this->request->controller()."/".$this->request->action());
        if(!defined('UID')){
            define('UID',is_login());
        }
        if(empty(UID) && !in_array($visit,$allowUrl)){
            $this->json_error(["code"=>0,"msg"=>"请先登录！"]);
        }else{
            model('user/user')->set_recommend();
        }
        $this->mold="wap";
	}

    private function json_error($data){
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit;
    }
}