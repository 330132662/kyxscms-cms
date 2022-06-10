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
use think\facade\Config;
use captcha\Captcha;

class Index extends Base
{
    public function index()
    {
    	$this->assign('menu', model('menu')->getMenus());
        return $this->fetch();
    }

    public function welcome()
    {
        $count['novel_count']=Db::name('novel')->count('id');
        $count['novel_today_count']=Db::name('novel')->whereTime('create_time', 'd')->count('id');
        $count['novel_today_count_update']=Db::name('novel')->whereTime('update_time', 'd')->count('id');
        $count['news_count']=Db::name('news')->count('id');
        $count['news_today_count']=Db::name('news')->whereTime('create_time', 'd')->count('id');
        $count['news_today_count_update']=Db::name('news')->whereTime('update_time', 'd')->count('id');
        $count['user_count']=Db::name('user')->count('id');
        $count['user_today_count']=Db::name('user')->whereTime('create_time', 'd')->count('id');
        $count['user_today_count_update']=Db::name('user')->whereTime('update_time', 'd')->count('id');
        $count['comment_count']=Db::name('comment')->count('id');
        $count['comment_today_count']=Db::name('comment')->whereTime('create_time', 'd')->count('id');
        $novle=model('common/api')->get_novel('','update_time desc',17,'','','','','','');
        $comment = model('comment')->lists(['novel','news'],null,5)->toArray();
        $comment = model('comment')->get_tree($comment['data']);
        $this->assign('web',Config::get('web.'));
        $this->assign('count',$count);
        $this->assign('novle',$novle);
        $this->assign('comment',$comment);
    	return $this->fetch();
    }

    public function login($username = null, $password = null, $code = null)
    {
        if($this->request->isPost()){
        	$captcha = new Captcha();
			if(!$captcha->check($code)){
				$this->error('验证码错误！');
			}
            $uid = model('index')->login($username, $password);
            if(0 < $uid){
                $this->success('登录成功！', url('admin/index/index'));
            } else { //登录失败
                switch($uid) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break;
                }
                $this->error($error);
            }
        } else {
            if(is_login('admin')){
                $this->redirect('admin/index/index');
            }else{
                return $this->fetch();
            }
        }
    }
	
	/* 退出登录 */
    public function logout()
    {
        if(is_login('admin')){
            model('index')->logout();
            session('[destroy]');
            $this->success('退出成功！', url('admin/index/login'));
        } else {
            $this->redirect('admin/index/login');
        }
    }

    public function verify()
    {
    	$captcha = new Captcha();
        return $captcha->entry();
    }
}