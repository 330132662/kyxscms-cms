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

class Bookshelf extends UserBase
{

    public function index(){
    	$Bookshelf=model('bookshelf');
    	$list=$Bookshelf->lists(UID,10,($this->mold=='web'?false:true));
    	$this->assign('list',$list);
        return $this->fetch($this->user_tplpath.'bookshelf.html');
    }

    public function add($novel_id){
        $Bookshelf=model('bookshelf');
        $result=$Bookshelf->add(UID,$novel_id);
        if($result !== false){
            return $this->success('成功添加书架！');
        }else{
            $this->error($Bookshelf->getError());
        }
    }

    public function check($novel_id){
        $Bookshelf=model('bookshelf');
        $result=$Bookshelf->check($novel_id);
        if($result !== false){
            return $this->success('成功添加书架！');
        }else{
            $this->error($Bookshelf->getError());
        }
    }


    public function del(){
        $Bookshelf=model('bookshelf');
        $result = $Bookshelf->del();
        if($result !== false){
            return $this->success('删除成功！','user/bookshelf/index');
        }else{
            $this->error($Bookshelf->getError());
        }
    }
}
