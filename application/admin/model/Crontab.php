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
use app\admin\validate\Crontab as CrontabValidate;

class Crontab extends Model{

    protected $autoWriteTimestamp = true;
    protected $json = ['content'];
    protected $jsonAssoc = true;

	public function info($map){
    	$info=Crontab::where($map)->find();
		return $info;
	}

	public function edit($data){
        $validate = new CrontabValidate;
        if (!$validate->check($data)) {
            $this->error=$validate->getError();
            return false;
        }
        $Crontab = new Crontab();
        if(empty($data['id'])){
            $result = $Crontab->allowField(true)->save($data);
        }else{
            $result = $Crontab->allowField(true)->isUpdate(true)->save($data);
        }
        if(false === $result){
            $this->error=$Crontab->getError();
            return false;
        }
        return $result;
    }

    public function del($id){
        if ( empty($id) ) {
            $this->error='定时任务还为添加无法取消！';
            return false;
        }
        $map = ['id' => $id];
        $result = Crontab::where($map)->delete();
        if(false === $result){
            $this->error=Crontab::getError();
            return false;
        }else{
            return $result;
        }
    }
}