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
use org\Oauth;

class ApiBase extends Controller{
    protected $mold;
    protected $home_tplpath;

    protected function initialize(){
        if(!Config::get('web.close')){
            $this->json_error(["code"=>0,"msg"=>Config::get('web.close_tip')]);
        }
        $allowUrl = ['api/novel/chapter_list',
                     'api/novel/chapter',
                     'api/news/content',
                     'api/common/system',
                     'api/common/config',
                     'api/common/addons',
                     'api/common/addons_list',
                    ];
        $visit = strtolower($this->request->module()."/".$this->request->controller()."/".$this->request->action());
        if(in_array($visit,$allowUrl)){
            $api_key=$this->request->param('api_key');
            if(empty($api_key)){
                $user_ip=$this->request->ip();
                $allow_ip=['127.0.0.1','localhost'];
                if(!in_array($user_ip,$allow_ip)){
                    $this->json_error(["code"=>0,"msg"=>"未经授权"]);
                }
                $oauth = new Oauth();
                $check_deduct=$oauth->checkDeduct($this->request->controller());
                $check_deduct=json_decode($check_deduct,true);
                if($check_deduct['code']!=1){
                    $this->json_error($check_deduct);
                }
            }else{
                if(Config::get('web.api_key')!=$api_key){
                    $this->json_error(["code"=>0,"msg"=>"未经授权"]);
                }
            }
        }
        if(!defined('UID')){
            define('UID',is_login());
        }
        if(UID){
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