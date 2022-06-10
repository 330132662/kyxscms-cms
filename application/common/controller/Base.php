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
use net\Http;

class Base extends Controller{
    protected $mold;
    protected $home_tplpath;

    protected function initialize(){
        if(!Config::get('web.close')){
            $this->error(Config::get('web.close_tip'));
        }
        if(!defined('UID')){
            define('UID',is_login());
        }
        $this->mold=($this->request->isMobile())?'wap':'web';
        if(checkDomain($this->request->domain(),Config::get('web.wap_url')) && $this->mold!='wap'){
            $this->mold='wap';
        }elseif(Config::get('web.wap_url') && !checkDomain($this->request->domain(),Config::get('web.wap_url')) && $this->mold=='wap'){
            if(strpos(Config::get('web.wap_url'),'://') !==false){
                $this->redirect(Config::get('web.wap_url'),302);
            }else{
                $this->redirect("http://".Config::get('web.wap_url'),302);
            }
        }
        $map[] = ['','exp',Db::raw('find_in_set("'.$this->mold.'",`mold`)')];
        $map[] = ['default','=',1];
        $tpl_name=Db::name('Template')->where($map)->value('name');
        $this->home_tplpath=Config::get('web.default_tpl').'/'.$tpl_name.'/';
        $this->view->config(['cache_path'=>Env::get('runtime_path').'temp'.DIRECTORY_SEPARATOR.'home'.DIRECTORY_SEPARATOR.$this->mold.DIRECTORY_SEPARATOR]);
        $this->assign('web',Config::get('web.'));
        if(UID){
            model('user/user')->set_recommend();
            $this->assign('user',model('user/user')->get_info());
        }
        $map=[];
        $map[] = ['','exp',Db::raw('find_in_set("'.$this->mold.'",`mold`)')];
        $map[] = ['status','=',1];
        $map[] = ['group','=','author'];
        $author_show=Db::name('addons')->where($map)->value('name');
        $this->assign('author_show',$author_show);
        $this->assign('mold',$this->mold);
        $this->assign('home_tplpath','/'.$this->home_tplpath);
	}

    protected function fetch($template = '', $vars = [], $config = [], $renderContent = false)
    {
        if(!$this->check_template($template)){
            return urldecode('%e8%af%b7%e8%b4%ad%e4%b9%b0%e6%a8%a1%e7%89%88!');
        }
        $fetch=$this->view->fetch($template, $vars, $config, $renderContent);
        $fetch=model('common/DataOperation')->replace_str($this->view,$fetch);
        if(!in_array(strtolower($this->request->controller()."/".$this->request->action()),['comment/tree','comment/lists','chapter/lists'])){
            if($this->mold=="web"){
                $fetch.='<script src="/public/static/layer/layer.js"></script>';
            }else{
                $fetch.='<script src="/public/static/layer_mobile/layer.js"></script>';
            }
            $fetch.='<script type="text/javascript">';
            $fetch.='var view={controller:"'.strtolower($this->request->controller()).'",action:"'.strtolower($this->request->action()).'",mold:"'.$this->mold.'"};';
            if(in_array(strtolower($this->request->controller()."/".$this->request->action()),['chapter/index','novel/index'])){
                $book_id=$this->view->__get('id');
                $serialize=$this->view->__get('serialize');
                $is_bookshelf=$this->view->__get('is_bookshelf');
                $fetch.='var book_id='.$book_id.',user_id='.UID.',is_bookshelf='.($is_bookshelf?$is_bookshelf:0).',serialize='.$serialize.';';
            }
            $fetch.='</script>';
            $fetch.='<script src="/public/home/js/home.js"></script>';
            $fetch.=model('common/DataOperation')->print_js();
        }
        if(Config::get('web.html_cache')){
            if(!in_array(strtolower($this->request->controller()),['search','comment','chapter'])){
                $key=md5($this->request->url(true));
                if($key){
                    $options = [
                        'expire'=>  0,
                        'path'  =>  Env::get('runtime_path').'html'.DIRECTORY_SEPARATOR.$this->mold.DIRECTORY_SEPARATOR,
                    ];
                    $html_cache=Cache::connect($options)->get($key);
                    if(!$html_cache){
                        Cache::connect($options)->set($key,$fetch);
                        $html_cache=$fetch;
                    }
                    return $html_cache;
                }
            }
        }else{
            return $fetch;
        }
    }

    protected function check_template($template){
        $check_template=Cache::get('check_template_'.$this->mold);
        if(empty($check_template)){
            $template_array=explode("/",$template);
            if($template_array && $template_array[1]==="home"){
                $url=Config::get('web.official_url').'/check_template/';
                $data=['template'=>$template_array[2],'client_id'=>Config::get('web.client_id'),'client_secret'=>Config::get('web.client_secret')];
                $content=Http::doPost($url,$data);
                if(empty($content)){
                    Cache::set('check_template_'.$this->mold,1,86400);
                    return false;
                }else{
                    Cache::set('check_template_'.$this->mold,2,2592000);
                    return true;
                }
            }
        }else{
            if($check_template==1){
                return false;
            }
        }
        return true;
    }
}