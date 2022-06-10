<?php
namespace app\admin\controller;

class Log extends Base
{

    public function index(){
        $CollectLog=model('Log');
        $list = $CollectLog->lists();
        $this->assign('list', $list);
        $this->assign('meta_title','操作日志');
        return $this->fetch();
    }
}