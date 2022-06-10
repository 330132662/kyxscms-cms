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

class Comment extends Model{

    protected $autoWriteTimestamp = true;

	public function info($id){
		$map['id'] = $id;
    	$info=Comment::where($map)->find();
		return $info;
	}

    public function lists($type='novel',$id=null,$limit=0){
        $map=[];
        $map=['status'=>1];
        $map=['type'=>$type];
        if($id){
            $map=['mid'=>$id];
        }
        $limit=$limit?$limit:Config::get('web.list_rows');
        $list=Comment::where($map)->order('up desc,id desc')->paginate($limit);
        return $list;
    }

	public function edit($data){
        if(empty($data['id'])){
            $result = Comment::allowField(true)->save($data);
        }else{
            $result = Comment::allowField(true)->isUpdate(true)->save($data);
        }
        if(false === $result){
            $this->error=Comment::getError();
            return false;
        }
        return $result;
    }

    public function del($id){
        $map = ['id' => $id];
        $result = Comment::where($map)->delete();
        if(false === $result){
            $this->error=Comment::getError();
            return false;
        }else{
            $sub_id=Comment::where(['pid'=>$id])->column('id');
            if($sub_id){
                $this->del($sub_id);
            }
            return $result;
        }
    }

    public function get_tree($list, $id = 0){
        foreach ($list as $key => $value) {
            $list[$key]['user']=model('user/user')->get_info($value['uid'])->toArray();
            $list[$key]['title']=Db::name($value['type'])->where(['id'=>$value['mid']])->value('title');
        }
        $list = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_', $root = $id);
        return $list;
    }
}