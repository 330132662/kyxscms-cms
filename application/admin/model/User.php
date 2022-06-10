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
use think\facade\Config;
use think\facade\Request;
use org\File;
use app\admin\validate\User as UserValidate;

class User extends Model{

    protected $autoWriteTimestamp = true;

	public function info($id){
		$map['id'] = $id;
    	$info=User::where($map)->find();
		return $info;
	}

    public function lists(){
        $map=[];
        if(Request::param('keywords')){
            $map[]  = ['username','like','%'.Request::param('keywords').'%'];
        }
        $status=Request::param('status');
        if(isset($status)){
            $map[] = ['status','=',$status];
        }
        $list=User::where($map)->order('id desc')->paginate(Config::get('web.list_rows'))->each(function($item, $key){
            $item->group = Db::name('user_group')->where([['exp_min','<=',$item->exp],['exp_max','>=',$item->exp],['status','=',1]])->value('name');
        });
        return $list;
    }

	public function edit($data){
        $validate = new UserValidate;
        if(empty($data['id'])){
            if (!$validate->scene('reg')->check($data)) {
                $this->error=$validate->getError();
                return false;
            }
            $data['password']=think_ucenter_md5($data['password']);
            $result = User::allowField(true)->save($data);
        }else{
            if(!empty($data['password'])){
                if (!$validate->scene('password')->check($data)) {
                    $this->error=$validate->getError();
                    return false;
                }
                $data['password']=think_ucenter_md5($data['password']);
            }else{
                if (!$validate->scene('edit')->check($data)) {
                    $this->error=$validate->getError();
                    return false;
                }
            }
            $result = User::allowField(true)->isUpdate(true)->save($data);
        }
        if(false === $result){
            $this->error=User::getError();
            return false;
        }
        return $result;
    }

    public function del($id){
        $map = ['id' => $id];
        $pic = User::where($map)->column('headimgurl');
        foreach ($pic as $value) {
            if(strpos($value, 'user-icon.png')===false){
                File::unlink(".".$value);
            }
        }
        $result = User::where($map)->delete();
        if(false === $result){
            $this->error=User::getError();
            return false;
        }else{
            Db::name('comment')->where(['uid'=>$id])->delete();
            Db::name('bookshelf')->where(['user_id'=>$id])->delete();
            return $result;
        }
    }
}