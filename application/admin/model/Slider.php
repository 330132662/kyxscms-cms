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
use think\facade\Config;
use app\admin\validate\Slider as SliderValidate;

class Slider extends Model{

    protected $autoWriteTimestamp = true;

    public function getTypeTextAttr($value,$data){
        $status = [0=>'web',1=>'wap',2=>'app'];
        return $status[$data['type']];
    }

	public function info($id){
		$map['id'] = $id;
    	$info=Slider::where($map)->find();
		return $info;
	}

    public function lists(){
        return Slider::order('sort asc')->paginate(Config::get('web.list_rows'));
    }

	public function edit(){
        $data=Request::post();
        $validate = new SliderValidate;
        if (!$validate->check($data)) {
            $this->error=$validate->getError();
            return false;
        }
        $Slider = new Slider();
        if(empty($data['id'])){
            $result = $Slider->allowField(true)->save($data);
        }else{
            $result = $Slider->allowField(true)->isUpdate(true)->save($data);
        }
        if(false === $result){
            $this->error=$Slider->getError();
            return false;
        }
        return $result;
    }

    public function del($id){
        $map = ['id' => $id];
        $result = Slider::where($map)->delete();
        if(false === $result){
            $this->error=Slider::getError();
            return false;
        }else{
            return $result;
        }
    }
}