<?php
namespace app\user\validate;
use think\Validate;
use think\Db;
use think\facade\Config;
use think\facade\Session;

class User extends Validate{
	protected $rule =   [
        'username'  => 'require|unique:user',
        'email' => 'require|email|unique:user',
        'password' => 'require|length:6,30',
        'repassword' => 'require|confirm:password',
        'protocol' => 'accepted',
        'curpassword' => 'require|checkUserPass:pass',
        'newpassword' => 'require|length:6,30',
        'confirmpassword' => 'require|confirm:newpassword',
        'pwdcode' => 'require|checkPwdCode:passw'
    ];

    protected $message  =   [
        'username.require' => '请先填写用户名',
        'username.unique' => '该用户名已注册',
        'email.require' => '请填写邮箱地址',
        'email.email' => '请填写正确邮箱地址',
        'email.unique' => '该邮箱已注册',
        'password.require' => '用户密码必须填写',
        'password.length'  => '用户密码长度必须在6-30个字符之间！',
        'repassword.require' => '重复密码必须填写',
        'repassword.confirm'  => '用户密码与重复密码不一致！',
        'protocol' => '抱歉不同意服务协议无法注册！',
        'curpassword.require' => '用户当前密码必须填写',
        'curpassword.checkUserPass'  => '用户当前密码不正确！',
        'newpassword.require' => '用户密码必须填写',
        'newpassword.length'  => '用户密码长度必须在6-30个字符之间！',
        'confirmpassword.require' => '确认新密码必须填写',
        'confirmpassword.confirm'  => '用户密码与确认新密码不一致！',
        'pwdcode.require' => '请必须填写验证码',
        'pwdcode.checkPwdCode' => '验证码错误',
    ];

    protected $scene = [
        'reg' =>['username','email','password','repassword','protocol'],
        'passw' =>['newpassword','confirmpassword'],
        'password' =>['curpassword','newpassword','confirmpassword'],
        'passwcode' =>['pwdcode'],
    ];

    public function sceneLogin(){
        $this->only(['username','password'])->remove('username','unique');
        return true;
    }

    public function sceneEdit(){
        return $this->only(['email'])->remove('email', 'unique');
    }

    protected function checkUserPass($value,$rule,$data){
        $password = Db::name('user')->where('id',UID)->value('password');
        if(think_ucenter_md5($value) === $password){
            return true;
        }
        return false;
    }

    protected function checkPwdCode($value,$rule,$data){
        if(empty($value)){
            return false;
        }
        $cell_code=Session::get('cell_code','email_'.$rule);
        if((time()-$cell_code['cell_time'])>180){
            Session::delete('cell_code','email_'.$rule);
            return false;
        }
        if($cell_code['cell_code'] == $value){
            Session::delete('cell_code','email_'.$rule);
            return true;
        }else{
            return false;
        }
    }
}