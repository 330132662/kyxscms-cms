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
use think\Db;
use think\Model;
use think\facade\Request;
use app\admin\validate\UserGroup as UserGroupValidate;

class UserGroup extends Model{

    // 设置json类型字段
    protected $json = ['json'];
    
    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

	public function info($id){
		$map['id'] = $id;
    	$info=UserGroup::where($map)->find();
		return $info;
	}

    public function lists(){
        $list=UserGroup::paginate(config('web.list_rows'))->each(function($item, $key){
            $item->count = Db::name('user')->where('exp','between',[$item->exp_min,$item->exp_max])->count('id');
        });
        return $list;
    }

	public function edit(){
        $data=Request::post();
        $validate = new UserGroupValidate;
        if (!$validate->check($data)) {
            $this->error=$validate->getError();
            return false;
        }
        $UserGroup = new UserGroup();
        if(empty($data['id'])){
            $result = $UserGroup->allowField(true)->save($data);
        }else{
            $result = $UserGroup->allowField(true)->isUpdate(true)->save($data);
        }
        if(false === $result){
            $this->error=$UserGroup->getError();
            return false;
        }
        return $result;
    }

    public function del($id){
        $map = ['id' => $id];
        $result = UserGroup::where($map)->delete();
        if(false === $result){
            $this->error=UserGroup::getError();
            return false;
        }else{
            return $result;
        }
    }
}