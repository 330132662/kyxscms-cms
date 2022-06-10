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

namespace app\admin\model;
use think\Model;
use think\facade\Request;
use think\facade\Config;
use net\Http;
use org\Oauth;

class UnionCash extends Model{

    private $oauth_access_token;

    protected function initialize(){
        parent::initialize();
        $auth = new Oauth();
        $this->oauth_access_token="AuthorizationCode: OAuth =".$auth->getToken();
    }

    public function lists($state=0){
        $url=Config::get('web.official_url').'/union/cash/list/'.Config::get('web.list_rows');
        if($p=Request::param('page')){
            $url.='/'.$p;
        }
        $content=Http::doGet($url,30,$this->oauth_access_token);
        $content=json_decode($content,true);
        if(empty($content['code'])){
            $this->error=$content['msg'];
            return false;
        }else{
            return $content;
        }
    }

    public function add($data){
        $url=Config::get('web.official_url').'/union/cash/add';
        $content=Http::doPost($url,$data,30,$this->oauth_access_token);
        $content=json_decode($content,true);
        if(empty($content['code'])){
            $this->error=$content['msg'];
            return false;
        }else{
            return true;
        }
    }
}