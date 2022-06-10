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

namespace app\user\model;

use think\Model;
use think\facade\Request;
use think\facade\Cookie;

class Recentread extends Model
{
    protected $autoWriteTimestamp = true;

	public function info($id){
		$data=Cookie::get('read_log');
		return $data[$id];
	}

    public function lists($limit=10){
        $readlog=[];
        $page=Request::get('page',1);
        $data=Cookie::get('read_log');
        if($data){
        	if(!is_array($data)){
        		Cookie::delete('read_log');
        		return false;
        	}
            $data=array_reverse($data,true);
            $count=count($data);
            $data=array_slice($data,($page-1)*$limit,$limit,true);
            foreach ($data as $key=>$val){
                $novel=model('common/api')->novel_detail($key);
                if($novel !== false){
                    $read=explode('|',$val);
                    $readlog[]=['novel_id'=>$key,'chapter_id'=>$read[0],'read_time'=>$read[2],'book'=>$novel,'reader_url'=>url('home/chapter/index',['id'=>$read[0],'key'=>$read[1]])];
                }
            }
            return ['count'=>$count,'list'=>$readlog];
        }
    }

    public function add($novel_id,$chapter_id,$chapter_key){
        $data=Cookie::get('read_log');
        if(!is_array($data)){
            Cookie::delete('read_log');
        }
        $data[$novel_id]=$chapter_id.'|'.$chapter_key.'|'.time();
        if(count($data)>40){
            array_shift($data);
        }
        model('user/bookshelf')->chapter_update($novel_id,$chapter_id,$chapter_key);
        Cookie::forever('read_log',$data);
    }

    public function del($id){
        $data=Cookie::get('read_log');
        unset($data[$id]);
        Cookie::forever('read_log',$data);
    }
}