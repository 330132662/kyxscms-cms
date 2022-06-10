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

namespace app\home\controller;

use app\common\controller\Base;
use think\facade\Cookie;

class Novel extends Base
{
    public function index()
    {
        Cookie::set('__forward__',$this->request->url());
        $id=$this->request->param('id');
        $info=model('common/api')->novel_detail($id);
        if(!$info){
            $error = model('common/api')->getError();
            $this->error(empty($error) ? '未找到该小说！' : $error,url('Home/Index/index'));
        }
        if(empty($info['template'])){
            $tpl=model('common/api')->get_tpl($info['cid'],'template_detail');
            if(empty($tpl)){
                $error = model('common/api')->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        }else{
            $tpl=$info['template'];
        }
        if(!empty($info['link'])){
        	$this->redirect($info['link'],302);
        }
        model('common/api')->hits($id,'novel');
        $is_bookshelf=model('user/bookshelf')->check($info['id']);
        $this->assign('pos',1);
        $this->assign('type','novel');
        $this->assign($info);
        $this->assign('reader_url',model('common/api')->novel_reader_url($info['id']));
        $this->assign('is_bookshelf',$is_bookshelf?$is_bookshelf:0);
        $this->assign('add_bookshelf','onclick=add_bookshelf()');
        return $this->fetch($this->home_tplpath.$tpl);
    }

    public function digg($id,$digg){
        $return=model('common/api')->digg($id,'novel',$digg);
        if($return){
           return $this->success($digg.'+1');
        }else{
           $this->error('请不要重复操作！');
        }
    }
}
