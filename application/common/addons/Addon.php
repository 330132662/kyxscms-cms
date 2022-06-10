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

use think\Controller;
use think\facade\Config;
use think\facade\Env;
use think\Db;

/**
 * 插件类
 */
class Addon extends Controller{

    public $mold            =   '';
    public $info                =   [];
    public $addon_path          =   '';
    public $config_file         =   '';
    public $custom_config      =   '';

    protected function initialize(){
        $this->addon_path = Env::get('root_path').'addons/'.$this->getName().'/';
        if(is_file($this->addon_path.'config.php')){
            $this->config_file = $this->addon_path.'config.php';
        }
        $this->mold=($this->request->isMobile())?'wap':'web';
        $this->view->config(['cache_path'=>Env::get('runtime_path').'temp'.DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.$this->getName().DIRECTORY_SEPARATOR.$this->mold.DIRECTORY_SEPARATOR]);
        $this->assign('web',Config::get('web.'));
        $this->assign('mold',$this->mold);
        $this->assign('addon_path','/addons/'.$this->getName().'/');
    }

    final public function getName(){
        $class = get_class($this);
        return explode('\\',$class)[1];
    }

    final public function checkInfo(){
        $info_check_keys = array('name','title','description','status','author','version','group','mold');
        foreach ($info_check_keys as $value) {
            if(!array_key_exists($value, $this->info))
                return false;
        }
        return true;
    }

    final public function fetch($template = '', $vars = [], $config = [], $renderContent = false){
        $this->view->config([
            'view_path'=>'./addons/'.$this->getName().'/',
            'tpl_replace_string'  =>  [
                '__STATIC__' => '/public/static',
                '__ADMIN__'    => '/public/admin',
            ]
        ]);
        echo $this->view->fetch($template, $vars, $config, $renderContent);
    }

    /**
     * 获取插件的配置数组
     */
    final public function getConfig($name=''){
        static $_config = [];
        if(empty($name)){
            $name = $this->getName();
        }
        if(isset($_config[$name])){
            return $_config[$name];
        }
        $config =   [];
        $map['name']    =   $name;
        $map['status']  =   1;
        $config  =   Db::name('Addons')->where($map)->value('config');
        if($config){
            $config   =   json_decode($config, true);
        }else{
            if(is_file($this->addon_path.'config.php')){
                $temp_arr = include $this->config_file;
                foreach ($temp_arr as $key => $value) {
                    if($value['type'] == 'group'){
                        foreach ($value['options'] as $gkey => $gvalue) {
                            foreach ($gvalue['options'] as $ikey => $ivalue) {
                                $config[$ikey] = $ivalue['value'];
                            }
                        }
                    }else{
                        $config[$key] = $temp_arr[$key]['value'];
                    }
                }
            }
        }
        $_config[$name]     =   $config;
        return $config;
    }

    //必须实现安装
    public function install(){

    }

    //必须卸载插件方法
    public function uninstall(){

    }
}
