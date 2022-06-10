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

namespace app\common\model;
use think\Model;
use think\Db;
use think\facade\Config;
use think\facade\Cache;
use net\Http;
use org\Oauth;

class DataOperation extends Model{

    public function after($type,$model,$data){
        $map = ['status'=>1,'group'=>'data_operation'];
        $addons = Db::name('Addons')->where($map)->column('name','id');
        foreach ($addons as $key => $value) {
            $addons_class = get_addon_class($value);
            if(class_exists($addons_class)){
                $addon = new $addons_class();
                $addon->run(['type'=>$type,'model'=>$model,'data'=>$data]);
            }
        }
    }

    public function replace_str($view,$html){
        $map = ['status'=>1,'group'=>'home_replace_html'];
        $addons = Db::name('Addons')->where($map)->column('name','id');
        foreach ($addons as $key => $value) {
            $addons_class = get_addon_class($value);
            if(class_exists($addons_class)){
                $addon = new $addons_class();
                $html=$addon->run(['view'=>$view,'html'=>$html]);
            }
        }
        return $html;
    }

    public function print_js(){
        $html='';
        $map = ['status'=>1,'group'=>'home_js'];
        $addons = Db::name('Addons')->where($map)->column('name','id');
        foreach ($addons as $key => $value) {
            $addons_class = get_addon_class($value);
            if(class_exists($addons_class)){
                $addon = new $addons_class();
                $html.=$addon->run();
            }
        }
        return $html;
    }
}