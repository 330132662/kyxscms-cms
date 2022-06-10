<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Cache;
use think\facade\Config;
use org\Oauth;

class Upgrade extends Base{

    protected $beforeActionList = [
        'checkAuth'  =>  ['only'=>'lists,insert'],
    ];

    protected function checkAuth(){
        $auth = new Oauth();
        if(!$auth->checkAuth()){
            return false;
        }
    }

    public function index(){
        $version  = model('upgrade')->version(Config::get('web.version'));
        if($this->request->isAjax()){
            if($version['update']){
                return json(['code'=>1,'version'=>$version],'200');
            }else{
                return json(['code'=>0,'version'=>''],'200');
            }
        }else{
           $this->assign('info', $version);
            $this->assign('meta_title', '在线升级');
            return $this->fetch();
        }
        
    }
    
    public function lists($id,$type,$name=null,$version=null){
        Cache::rm('update_list');
        $version  = model('upgrade')->version($version?$version:Config::get('web.version'),$name);
        $upList = model('upgrade')->upContent($id,$type);
        if(!empty($upList['error'])){
            $this->error($upList['error']);
        }
        $this->assign('version', $version);
        $this->assign('upList', $upList);
        $this->assign('meta_title', '在线升级');
        return $this->fetch();
    }

    public function insert($id){
        Cache::rm('update_list');
        $inList = model('upgrade')->upContent($id,null,'insert');
        if(!empty($inList['error'])){
            $this->error($inList['error'],'');
        }
        if(!empty($inList['pay'])){
            $content='<script type="text/javascript" src="__STATIC__/jquery/jquery.min.js"></script><script type="text/javascript" src="__STATIC__/layer/layer.js"></script><script type="text/javascript">var layer_index = parent.layer.getFrameIndex(window.name);layer.confirm("您的积分不购？", {btn: ["购买积分","以后在说"]}, function(){parent.layer.title("{$meta_title}", layer_index);parent.layer.style(layer_index,{width: "960px",height: "720px"});parent.layer.iframeSrc(layer_index, "{$url}");}, function(index){layer.close(index);parent.layer.close(layer_index);});
                </script>';
            return $this->display($content,['meta_title'=>'购买积分','url'=>url('admin/union/integral')]);
        }
        $this->assign('id', $id);
        $this->assign('inList', $inList);
        $this->assign('meta_title', '在线安装');
        return $this->fetch();
    }

    public function version($version,$name=null){
        $version  = model('upgrade')->version($version,$name);
        return json($version,'200');
    }

    public function update(){
        $Upgrade=model('upgrade');
        if(false !== $up_return=$Upgrade->updates()){
            return json(['code'=>1,'number'=>$up_return],200);
        }else{
            return json(['code'=>0,'error'=>$Upgrade->getError()],200);
        }
    }
    
    public function install($model=null){
        $Upgrade=model('upgrade');
        if($model=='insert'){
            $return=$Upgrade->insert_install($this->request->param('id'));
        }else{
            $return=$Upgrade->install();
        }
        if($return==true){
            return $this->success('安装完成！','');
        }else{
            $this->error($Upgrade->getError(),'');
        }
    }
}