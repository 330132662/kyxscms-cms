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

namespace app\admin\model;
use think\Model;
use think\Db;

class Tool extends Model{

    public function datadel_novel(){
        $novel=Db::name('novel')->field('id')->select();
        foreach ($novel as $key => $value) {
            model('novel')->del($value['id']);
        }
	}

    public function datadel_news(){
        $news=Db::name('news')->field('id')->select();
        foreach ($news as $key => $value) {
            model('news')->del($value['id']);
        }
    }

    public function datadel_user(){
        $user=Db::name('user')->field('id')->select();
        foreach ($user as $key => $value) {
            model('user')->del($value['id']);
        }
    }
}