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
use org\File;
use app\admin\validate\Ad as AdValidate;

class Ad extends Model{

    protected $autoWriteTimestamp = true;
    protected $insert = ['status'=>1];

	public function info($id){
		$map['id'] = $id;
    	$info=Ad::where($map)->find();
		return $info;
	}

    public function lists(){
        $map = [];
        $map[] = ['status','=',1];
        $list=Ad::where($map)->order('update_time desc')->paginate(config('web.list_rows'));
        return $list;
    }

	public function edit($data){
        $validate = new AdValidate;
        if (!$validate->check($data)) {
            $this->error=$validate->getError();
            return false;
        }
        $Ad = new Ad();
        if(empty($data['id'])){
            $result = $Ad->allowField(true)->save($data);
        }else{
            $result = $Ad->allowField(true)->isUpdate(true)->save($data);
        }
        if(false === $result){
            $this->error=$Ad->getError();
            return false;
        }
        return $result;
    }

    public function del($id){
        $map = ['id' => $id];
        $result = Ad::where($map)->delete();
        if(false === $result){
            $this->error=Ad::getError();
            return false;
        }else{
            return $result;
        }
    }
}