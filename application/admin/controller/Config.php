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

namespace app\admin\controller;
use think\Db;
use think\facade\Cache;

class Config extends Base 
{
    /**
     * 批量保存配置
     */
    public function save($config){
        if($config && is_array($config)){
            foreach ($config as $name => $value) {
                $map = ['name' => $name];
                if(is_array($value)){
                    $value=implode(",", $value);
                }
                Db::name('config')->where($map)->setField('value', $value);
            }
        }
        Cache::rm('config_data');
        $this->success('保存成功！');
    }

    // 获取某个标签的配置参数
    public function index() {
        $id     =   $this->request->param('id',1);
        $list   =   Db::name("Config")->where(['group'=>$id,'display'=>1])->field('id,name,title,extra,value,remark,type')->order('sort')->select();
        if($list) {
            $this->assign('list',$list);
        }
        $this->assign('id',$id);
        $this->assign('meta_title','系统设置');
        return $this->fetch();
    }
}
