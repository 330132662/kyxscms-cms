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
use think\facade\Config;
use think\facade\Session;
use think\Db;
use captcha\Captcha;

class User extends UserBase
{

    public function login($username = null, $password = null){
        if(Config::get('web.user_model_status')!=1){
            $this->error('已经关闭会员模块',url('home/index/index'));
        }
        if($this->request->isPost()){
            $User = model('user');
            $result = $User->login($username, $password);
            if($result !== false){
                $url=Cookie('__forward__')?Cookie('__forward__'):url('home/index/index');
                return $this->success('登录成功！', $url);
            } else { //登录失败
                $this->error($User->getError());
            }
        } else {
            if(is_login()){
                $this->redirect('home/index/index');
            }else{
                return$this->fetch($this->user_tplpath.'login.html',[],['taglib_build_in'=>'app\common\taglib\HomeTag,cx']);
            }
        }
    }

    /* 退出登录 */
    public function logout(){
        if(is_login()){
            model('User')->logout();
            return $this->success('退出成功！', url('login'));
        } else {
            $this->redirect('login');
        }
    }

    public function reg(){
        if(!Config::get('web.user_allow_register')){
            $this->error('注册已经关闭，请稍后注册~', url('home/index/index'));
        }
        if($this->request->isPost()){
            $data=$this->request->post();
            $User = model('user');
            $result = $User->reg($data);
            if($result !== false){
                return $this->success('用户注册成功！',url('user/user/login'));
            } else {
                $this->error($User->getError(),'');
            }
        }else{
            return $this->fetch($this->user_tplpath."register.html");
        }
    }

    public function forgetpwd($step=null){
        if($this->request->isPost()){
            $data=$this->request->post();
            switch ($step) {
                case 1:
                    $captcha = new Captcha();
                    if(!$captcha->check($data['code'])){
                        $this->error('验证码错误','');
                    }
                    if(!model('user')->check_require('username',$data['username'])){
                        $this->error('无此用户！','');
                    }else{
                        $user=Db::name('user')->where(['username'=>$data['username']])->field('id,email')->find();
                        Session::set('forget_pwd',['uid'=>$user['id'],'email'=>$user['email']]);
                        return $this->success('进入第二步验证用户！',url('forgetpwd',['step'=>2]));
                    }
                    break;
                case 2:
                    $result = $this->validate($data,'User.passwcode');
                    if(true !== $result){
                        $this->error($result);
                    }
                    Session::set('forget_pwd.adopt',1);
                        return $this->success('进入第三步修改用户密码！',url('forgetpwd',['step'=>3]));
                    break;
                case 3:
                    if(!Session::get('forget_pwd.adopt')){
                        $this->error('请不要乱走',url('index/index'));
                    }
                    $captcha = new Captcha();
                    if(!$captcha->check($data['code'])){
                        $this->error('验证码错误','');
                    }
                    $result = $this->validate($data,'User.passw');
                    if(true !== $result){
                        $this->error($result,'');
                    }
                    model('user')->forgetpwd();
                    return $this->success('用户密码修改成功！',url('forgetpwd',['step'=>4]));
                    break;
            }
        }
        return $this->fetch($this->user_tplpath."forgetpwd.html");
    }

    public function email_code($email,$type){
        $result = $this->validate(['email' => $email],['email' => 'require|email']);
        if(true !== $result){
            $this->error('邮箱格式错误！');
        }
        $map=[];
        $map[] = ['group','=','email'];
        $map[] = ['status','=',1];
        $map[] = ['','exp',Db::raw('find_in_set("'.$this->mold.'",`mold`)')];
        $addons_name = Db::name('Addons')->where($map)->value('name');
        $addons_class = get_addon_class($addons_name);
        if(!class_exists($addons_class))
            $this->error('插件不存在');
        $code=$this->randString();
        $this->cell_code($code,"email_".$type);
        $addon = new $addons_class();
        $params['to']=$email;
        switch ($type) {
            case 'bind':
                $params['title']=Config::get('web.meta_title').'-邮箱绑定';
                break;
            case 'reg':
                $params['title']='感谢您注册'.Config::get('web.meta_title').'-帐号注册验证码邮件';
                break;
            case 'passw':
                $params['title']=Config::get('web.meta_title').'-密码找回';
                break;
        }
        $addon_config=$addon->getConfig();
        $params['content']=str_replace(['{$email}','{$code}','{$web_url}','{$web_name}'], [$email,$code,Config::get('web.url'),Config::get('web.meta_title')], $addon_config['tpl_'.$type]);
        if($addon->$addons_name($params)==true){
            return $this->success('验证码发送成功,请到你的邮箱查看！','');
        }else{
            $this->error('邮件发送失败！');
        }
    }

    public function pact(){
        return $this->fetch($this->user_tplpath."pact.html");
    }

    public function check_require($field,$value){
        if(model('user')->check_require($field,$value)){
            $this->error('已经存在！');
        }
        return $this->success('可以注册！');
    }

    public function check_code($value){
        $captcha = new Captcha(['reset'=>false]);
        if(!$captcha->check($value)){
            $this->error('验证码错误！');
        }
        return $this->success('验证码正确！');
    }

    public function verify(){
        $captcha = new Captcha();
        return $captcha->entry();
    }

    public function info(){
        $User = model('user');
        if($this->request->isPost()){
            $result = $User->edit();
            if($result !== false){
                return $this->success('修改成功！','user/user/info');
            }else{
                $this->error($User->getError());
            }
        }else{
            return $this->fetch($this->user_tplpath.'info.html');
        }
    }

    public function password(){
        if($this->request->isPost()){
            $User = model('user');
            $result = $User->password();
            if($result !== false){
                return $this->success('密码修改成功！','user/user/password');
            }else{
                $this->error($User->getError());
            }
        }else{
            return $this->fetch($this->user_tplpath.'password.html');
        }
    }

    public function upload_head(){
        $file = $this->request->file('file');
        $info = $file->validate(['ext'=>'jpg,jpeg,png,gif,webp,bmp','type'=>'image/jpeg,image/png,image/gif,image/webp,image/bmp'])->rule([$this,'head_name'])->move(config('web.upload_path').'user/head');
        if($info){
            $return['code'] = 1;
            $return['path'] = substr(config('web.upload_path'),1).'user/head/'.str_replace('\\','/',$info->getSaveName());
        } else {
            $return = ['code' => 0,'msg' => $file->getError()];
        }
        return json($return);
    }

    public function head_name(){
        return "user_haed_".UID.".png";
    }

    public function crop_img($crop){
        $return  = ['code' => 1, 'msg' => '头像裁剪成功', 'path' => ''];
        if(!isset($crop) && empty($crop)){
            $return = ['code' => 0, 'msg' => '参数错误！'];
        }
        $info = model('User')->crop_img($crop);
        $return['path'] = $info;
        return json($return);
    }

    /**
     * 投推荐票
     * @param  integer $bookid 书籍ID
     * @param  integer $cnt 推荐票数量
     * @return string       
     */
    public function vote_recom_ticket($bookid,$cnt){
        $User = model('user');
        $result = $User->vote_recom_ticket($bookid,$cnt);
        if($result !== false){
            return $this->success('成功投出'.$cnt.'张推荐票！');
        }else{
            $this->error($User->getError());
        }
    }

    /**
     * 增加经验积分
     * @return string       
     */
    public function add_exp_points(){
        $User = model('user');
        $add_exp_time = Session::get('add_exp_time');
    	$res = time() - intval($add_exp_time);
    	session('add_exp_time',time());
    	if($res<60){
    		$this->error('请不要频繁操作!');
    	}
        $result = $User->add_exp_points();
        if($result !== false){
            return $this->success('增加积分和经验成！');
        }else{
            $this->error($User->getError());
        }
    }

    /**
     * 获取随机位数数字
     * @param  integer $len 长度
     * @return string       
     */
    protected function randString($len = 6){
        $chars = str_repeat('0123456789', $len);
        $chars = str_shuffle($chars);
        $str   = substr($chars, 0, $len);
        return $str;
    }

    protected function cell_code($code,$type){
        $session = [];
        $session['cell_code'] = $code;
        $session['cell_time'] = time();
        session('cell_code', $session,$type);
    }
}
