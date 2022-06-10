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

namespace app\install\controller;
use think\Controller;
use think\facade\Env;
use org\File;

class Index extends Controller{

    protected $lock='';

    protected function initialize(){
        $this->lock = Env::get('module_path').'data/install.lock';
        if(File::has($this->lock)){
            $this->error('已经成功安装了，请不要重复安装!','home/index/index');
        }
        $this->view->config(['cache_path'=>Env::get('runtime_path').'temp'.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR]);
    }

    public function index(){
        return $this->fetch();
    }

    /**
     * 安装环境检测
     */
    public function detect() {
        $list = check_dirfile();
        $funList = check_func();
        $this->assign('list', $list);
        $this->assign('funList', $funList);
        return $this->fetch();
    }

    /**
     * 配置系统
     */
    public function config() {
        return $this->fetch();
    }

    public function install() {
        set_time_limit(0);
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');
        echo $this->fetch();
        ob_end_flush();
        ob_implicit_flush(1);
        //检测信息
        $data = $this->request->post();
        check_data($data);
        show_msg('安装信息验证成功!');
        $db=check_db($data);
        show_msg('数据库检查完成...');
        write_config($data);
        create_tables($db,$data['prefix']);
        show_msg('创建基础数据库完成...');
        register_administrator($db,$data);
        File::put($this->lock,time());
        complete('安装程序执行完毕！');
        install_stop();
    }
}
