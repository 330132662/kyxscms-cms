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
use think\facade\Request;

class Addons extends Model{

    protected $autoWriteTimestamp = true;

	public function info($id){
		$map['id'] = $id;
    	$info=Addons::where($map)->find();
		return $info;
	}

    public function lists(){
        return Addons::order('sort asc')->paginate(config('web.list_rows'));
    }

	public function edit($data){
        if(empty($data['id'])){
            $result = Addons::allowField(true)->save($data);
        }else{
            $result = Addons::allowField(true)->isUpdate(true)->save($data);
        }
        if(false === $result){
            $this->error=Addons::getError();
            return false;
        }
        return $result;
    }

    public function del($id){
        $map = ['id' => $id];
        $result = Addons::where($map)->delete();
        if(false === $result){
            $this->error=Addons::getError();
            return false;
        }else{
            return $result;
        }
    }
}