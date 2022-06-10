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

namespace app\user\controller;

use app\common\controller\UserBase;
use think\Db;
use think\paginator\driver\Bootstrap;

class Recentread extends UserBase
{

    public function index(){
    	$Recentread=model('recentread');
    	$list=$Recentread->lists();
        $paginator = new Bootstrap($list['list'],10,$this->request->get('page',1),$list['count'],($this->mold=='web'?false:true),['path'=>url()]);
    	$this->assign('list',$list['list']);
        $this->assign('page',$paginator->render());
        return $this->fetch($this->user_tplpath.'recentread.html');
    }

    public function del($id){
        $Recentread=model('recentread');
        $result = $Recentread->del($id);
        if($result !== false){
            return $this->success('删除成功！','user/recentread/index');
        }else{
            $this->error($Recentread->getError());
        }
    }
}
