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

namespace app\api\controller;
use app\common\controller\ApiUserBase;
use think\facade\Config;
use think\facade\Session;
use think\Db;
use captcha\Captcha;

class User extends ApiUserBase
{

    public function info(){
        $user=model('user/user');
        $user->api_url=true;
        $user=$user->get_info();
        if($user){
            return json(["code"=>1,"data"=>$user]);
        }else{
            return json(["code"=>0,"msg"=>"暂无数据"]);
        }
    }

    public function login($username = null, $password = null){
        if(Config::get('web.user_model_status')!=1){
            return json(["code"=>0,"msg"=>"已经关闭会员模块"]);
        }
        if($this->request->isPost()){
            $User = model('user/user');
            $result = $User->login($username, $password);
            if($result !== false){
                return json(["code"=>1,"msg"=>"登录成功！"]);
            } else { //登录失败
                return json(["code"=>0,"msg"=>$User->getError()]);
            }
        }
    }

    /* 退出登录 */
    public function logout(){
        if(is_login()){
            model('user/user')->logout();
        } 
        return json(["code"=>1,"msg"=>"退出成功！"]);
    }

    public function reg(){
        if(!Config::get('web.user_allow_register')){
            return json(["code"=>0,"msg"=>'注册已经关闭，请稍后注册~']);
        }
        if($this->request->isPost()){
            $data=$this->request->post();
            $User = model('user/user');
            $result = $User->reg($data);
            if($result !== false){
                return json(["code"=>1,"msg"=>'用户注册成功！']);
            } else {
                return json(["code"=>0,"msg"=>$User->getError()]);
            }
        }
    }

    public function forgetpwd($step=null){
        if($this->request->isPost()){
            $data=$this->request->post();
            switch ($step) {
                case 1:
                    $captcha = new Captcha();
                    if(!$captcha->check($data['code'])){
                        return json(["code"=>0,"msg"=>'验证码错误']);
                    }
                    if(!model('user/user')->check_require('username',$data['username'])){
                        return json(["code"=>0,"msg"=>'无此用户！']);
                    }else{
                        $user=Db::name('user')->where(['username'=>$data['username']])->field('id,email')->find();
                        Session::set('forget_pwd',['uid'=>$user['id'],'email'=>$user['email']]);
                        return json(["code"=>1,"msg"=>'进入第二步验证用户！',"data"=>['uid'=>$user['id'],'email'=>$user['email']]]);
                    }
                    break;
                case 2:
                    $result = $this->validate($data,'app\user\validate\User.passwcode');
                    if(true !== $result){
                        return json(["code"=>0,"msg"=>$result]);
                    }
                    Session::set('forget_pwd.adopt',1);
                    return json(["code"=>1,"msg"=>'进入第三步修改用户密码！']);
                    break;
                case 3:
                    if(!Session::get('forget_pwd.adopt')){
                        return json(["code"=>0,"msg"=>'请不要乱走']);
                    }
                    $captcha = new Captcha();
                    if(!$captcha->check($data['code'])){
                        return json(["code"=>0,"msg"=>'验证码错误']);
                    }
                    $result = $this->validate($data,'app\user\validate\User.passw');
                    if(true !== $result){
                        return json(["code"=>0,"msg"=>$result]);
                    }
                    model('user/user')->forgetpwd();
                    return json(["code"=>1,"msg"=>'用户密码修改成功！']);
                    break;
            }
        }
    }

    public function email_code($email,$type){
        $result = $this->validate(['email' => $email],['email' => 'require|email']);
        if(true !== $result){
            return json(["code"=>0,"msg"=>'邮箱格式错误！']);
        }
        $map=[];
        $map[] = ['group','=','email'];
        $map[] = ['status','=',1];
        $map[] = ['','exp',Db::raw('find_in_set("'.$this->mold.'",`mold`)')];
        $addons_name = Db::name('Addons')->where($map)->value('name');
        $addons_class = get_addon_class($addons_name);
        if(!class_exists($addons_class))
            return json(["code"=>0,"msg"=>'插件不存在']);
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
            return json(["code"=>1,"msg"=>'验证码发送成功,请到你的邮箱查看！']);
        }else{
            return json(["code"=>0,"msg"=>'邮件发送失败！']);
        }
    }

    protected function check_require($field,$value){
        if(model('user/user')->check_require($field,$value)){
            return json(["code"=>0,"msg"=>'已经存在！']);
        }
        return json(["code"=>1,"msg"=>'可以注册！']);
    }

    public function verify(){
        $captcha = new Captcha();
        return $captcha->entry();
    }

    public function edit(){
        $User = model('user/user');
        if($this->request->isPost()){
            $result = $User->edit();
            if($result !== false){
                return json(["code"=>1,"msg"=>'修改成功！']);
            }else{
                return json(["code"=>0,"msg"=>$User->getError()]);
            }
        }
    }

    public function password(){
        if($this->request->isPost()){
            $User = model('user/user');
            $result = $User->password();
            if($result !== false){
                return json(["code"=>1,"msg"=>'密码修改成功！']);
            }else{
                return json(["code"=>0,"msg"=>$User->getError()]);
            }
        }
    }

    public function upload_head(){
        $file = $this->request->file('file');
        $info = $file->validate(['ext'=>'jpg,jpeg,png,gif,webp,bmp','type'=>'image/jpeg,image/png,image/gif,image/webp,image/bmp'])->rule([$this,'head_name'])->move(config('web.upload_path').'user/head');
        if($info){
            $return['code'] = 1;
            $return['path'] = $this->request->domain().substr(config('web.upload_path'),1).'user/head/'.str_replace('\\','/',$info->getSaveName());
        } else {
            $return = ['code' => 0,'msg' => $file->getError()];
        }
        return json($return);
    }

    protected function head_name(){
        return "user_haed_".UID.".png";
    }

    /**
     * 投推荐票
     * @param  integer $bookid 书籍ID
     * @param  integer $cnt 推荐票数量
     * @return string       
     */
    public function vote_recom_ticket($bookid,$cnt){
        $User = model('user/user');
        $result = $User->vote_recom_ticket($bookid,$cnt);
        if($result !== false){
            $return = ['code' => 1,'msg' => '成功投出'.$cnt.'张推荐票！'];
        }else{
            $return = ['code' => 0,'msg' => $User->getError()];
        }
        return json($return);
    }

    /**
     * 增加经验积分
     * @return string       
     */
    public function add_exp_points(){
        $User = model('user/user');
        $add_exp_time = Session::get('add_exp_time');
    	$res = time() - intval($add_exp_time);
    	session('add_exp_time',time());
    	if($res<60){
    		$return = ['code' => 0,'msg' => '请不要频繁操作!'];
    		return json($return);
    	}
        $result = $User->add_exp_points();
        if($result !== false){
            $return = ['code' => 1,'msg' => '增加积分和经验成！'];
        }else{
            $return = ['code' => 0,'msg' => $User->getError()];
        }
        return json($return);
    }


    /**
     * 评论
     * @return string       
     */
    public function comment($reply=0){
        $Comment=model('user/comment');
        $list=$Comment->lists(UID,10,false,$reply);
        return json(['code' => 1,'data' => $list]);
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
