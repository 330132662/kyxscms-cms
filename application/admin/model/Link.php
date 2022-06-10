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
use app\admin\validate\Link as LinkValidate;

class Link extends Model{

    protected $autoWriteTimestamp = true;

	public function info($id){
		$map['id'] = $id;
    	$info=Link::where($map)->find();
		return $info;
	}

	public function edit(){
        $data=Request::post();
        $validate = new LinkValidate;
        if (!$validate->check($data)) {
            $this->error=$validate->getError();
            return false;
        }
        $Link = new Link();
        if(empty($data['id'])){
            $result = $Link->allowField(true)->save($data);
        }else{
            $result = $Link->allowField(true)->isUpdate(true)->save($data);
        }
        if(false === $result){
            $this->error=$Link->getError();
            return false;
        }
        return $result;
    }

    public function del($id){
        $map = ['id' => $id];
        $result = Link::where($map)->delete();
        if(false === $result){
            $this->error=Link::getError();
            return false;
        }else{
            return $result;
        }
    }
}