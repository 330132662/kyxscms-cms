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

class Addons extends Base
{

    public function index(){
        $list = model('addons')->lists();
        $this->assign('list', $list);
        $this->assign('meta_title','插件列表');
        return $this->fetch();
    }

    /**
     * 设置插件页面
     */
    public function config($id){
        $Addons=model('addons');
        if($this->request->isPost()){
            $config=$this->request->post('config');
            $res = $Addons->edit(['id'=>$id,'config'=>json_encode($config)]);
            if($res !== false){
                return $this->success('保存成功');
            }else{
                $this->error($Addons->getError());
            }
        }else{
            $addon_find  =  $Addons->find($id)->toArray();
            if(!$addon_find)
                $this->error('插件未安装');
            $class = get_addon_class($addon_find['name']);
            if(!class_exists($class))
                exception("插件{$addon_find['name']}无法实例化,",100006);
            $addon_class  =   new $class;
            $addon_find['addon_path'] = $addon_class->addon_path;
            $addon_find['custom_config'] = $addon_class->custom_config;
            $db_config = $addon_find['config'];
            $addon_find['config'] = include $addon_class->config_file;
            if($db_config){
                $db_config = json_decode($db_config, true);
                foreach ($addon_find['config'] as $key => $value) {
                    if($value['type'] != 'group'){
                        if(empty($db_config[$key])){
                            $addon_find['config'][$key]['value'] = '';
                        }else{
                            $addon_find['config'][$key]['value'] = $db_config[$key];
                        }
                    }else{
                        foreach ($value['options'] as $gourp => $options) {
                            foreach ($options['options'] as $gkey => $value) {
                                if(empty($db_config[$gkey])){
                                    $addon_find['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = '';
                                }else{
                                    $addon_find['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                                }
                            }
                        }
                    }
                }
            }
            $this->assign('meta_title', '设置插件-'.$addon_class->info['title']);
            $this->assign('data',$addon_find);
            if($addon_find['custom_config'])
                $this->assign('custom_config', $this->fetch($addon_find['addon_path'].$addon_find['custom_config']));
            return $this->fetch();
        }
    }

    /**
     * 安装插件
     */
    public function install($addon_name,$addon_version=null){
        $Addons = model('addons');
        $class  = get_addon_class($addon_name);
        if(!class_exists($class))
            $this->error('插件不存在');
        $addon_class  =   new $class;
        $data = $addon_class->info;
        if(!$data || !$addon_class->checkInfo())//检测信息的正确性
            $this->error('插件信息缺失');
        $install_flag   =   $addon_class->install();
        if(!$install_flag){
            $this->error('执行插件预安装操作失败');
        }
        $data['create_time'] = time();
        if($addon_version){
            $data['version'] = $addon_version;
        }
        $data['config'] = json_encode($addon_class->getConfig());
        if(!empty($data['config']['exclusive'])){
            Db::name('Addons')->where(['group'=>$data['config']['group']])->update(['status' => 0]);
        }
        $res = $Addons->edit($data);
        if($res  !== false){
            cache('hooks', null);
            return $this->success('安装成功');
        }else{
            $this->error($Addons->getError());
        }
    }

    /**
     * 卸载插件
     */
    public function uninstall($id){
        $Addons=model('addons');
        $addon_find  =  $Addons->find($id);
        $class    =   get_addon_class($addon_find['name']);
        if(!class_exists($class))
            $this->error('插件不存在');
        $addon_class  =   new $class;
        $data = $addon_class->info;
        if(!$data || !$addon_class->checkInfo())//检测信息的正确性
            $this->error('插件信息缺失');
        $uninstall_flag   =   $addon_class->uninstall();
        if(!$uninstall_flag){
            $this->error('执行插件卸载操作失败');
        }
        $res=$Addons->del($id);
        if($res){
            del_dir_file('./addons/'.$addon_find['name'],true);
            return $this->success('卸载插件成功');
        }else{
            $this->error($Addons->getError());
        }
    }

    public function status(){
        $id = $this->request->param('id');
        $Addons=model('addons');
        $info = $Addons->info($id);
        if($info['group']=='storage'){
            Cache::rm('addons_storage');
        }
        if($info['status']==1){
            return $this->forbid('Addons');
        }else{
            if($info['exclusive']==1){
                Db::name('Addons')->where(['group'=>$info['group']])->update(['status' => 0]);
            }
            return $this->resume('Addons');
        }
    }
}