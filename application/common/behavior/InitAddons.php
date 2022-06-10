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
use think\facade\Env;
use think\facade\Hook;
use think\Loader;

// 初始化钩子信息
class InitAddons{
    // 行为扩展的执行入口必须是run
    public function run($params){
        if(is_file(APP_PATH . 'install/data/install.lock')){
            try {
                Loader::addNamespace(['addons' => Env::get('root_path').'addons'.DIRECTORY_SEPARATOR]);
                $data = cache('hooks');
                if(!$data){
                    $hooks = Db::name('Addons')->where(['status'=>1,'has_hook'=>1])->field('name')->select();
                    foreach ($hooks as $value) {
                        if($value){
                            Hook::add($value['name'],get_addon_class($value['name']));
                        }
                    }
                    cache('hooks',Hook::get());
                }else{
                    Hook::import($data,false);
                }
            }catch(Exception $e){}
        }
    }
}