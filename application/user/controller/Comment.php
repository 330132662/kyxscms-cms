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

class Comment extends UserBase
{

    public function index($reply=0){
    	$Comment=model('Comment');
    	$list=$Comment->lists(UID,10,($this->mold=='web'?false:true),$reply);
    	$this->assign('list',$list);
        return $this->fetch($this->user_tplpath.'comment.html');
    }
}
