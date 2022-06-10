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

class Index extends UserBase{

    public function index(){
        $recentread_list=model('recentread')->lists(2);
        $bookshelf_list=model('bookshelf')->lists(UID,2);
        $hotbook_list=model('common/api')->get_novel(false,'hits desc',12,false,false,false,false,false,true);
        $potbook_list=model('common/api')->get_novel(false,'update_time desc',4,4,false,false,false,false,true);
        $this->assign('recentread_list',$recentread_list['list']);
        $this->assign('bookshelf_list',$bookshelf_list);
        $this->assign('hotbook_list',$hotbook_list);
        $this->assign('potbook_list',$potbook_list);
        return $this->fetch($this->user_tplpath.'index.html');
    }
}
