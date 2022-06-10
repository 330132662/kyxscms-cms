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
use think\facade\Cache;
use app\admin\validate\Route as RouteValidate;

class Route extends Model{

	public function info($id){
		$map['id'] = $id;
    	$info=Route::where($map)->find();
		return $info;
	}

	public function edit(){
        $data=Request::post();
        $validate = new RouteValidate;
        if (!$validate->check($data)) {
            $this->error=$validate->getError();
            return false;
        }
        $Route = new Route();
        if(empty($data['id'])){
            $result = $Route->allowField(true)->save($data);
        }else{
            $result = $Route->allowField(true)->isUpdate(true)->save($data);
        }
        if(false === $result){
            $this->error=$Route->getError();
            return false;
        }else{
            Cache::rm('route_data');
        }
        return $result;
    }

    public function del($id){
        $map = ['id' => $id];
        $result = Route::where($map)->delete();
        if(false === $result){
            $this->error=Route::getError();
            return false;
        }else{
            return $result;
        }
    }
}