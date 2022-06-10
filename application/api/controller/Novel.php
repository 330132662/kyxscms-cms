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

namespace app\api\controller;
use app\common\controller\ApiBase;

class Novel extends ApiBase{

	public function category($cid=false,$type=0,$limit=false){
		$category=model('api/api')->category($cid,$type,$limit);
		return json(["code"=>1,"data"=>$category]);
	}

	public function filter($name,$type=false,$cid=false){
		$filter=model('common/api')->get_filter($name,$type,$cid);
		return json(["code"=>1,"data"=>$filter]);
	}

	public function screen($id=false){
		$api=model('common/api');
		$api->api_url=true;
		$data['type']=$api->get_filter("type",0,false);
		if(!empty($id)){
			$data['branch']=$api->get_filter("type",0,$id);
		}
		$data['serialize']=$api->get_filter("serialize",0,$id);
		$data['size']=$api->get_filter("size",0,$id);
		$data['update']=$api->get_filter("update",0,$id);
		return json(["code"=>1,"data"=>$data]);
	}

	public function lists($id=false,$order='update_time desc',$limit=20,$pos=false,$time=false,$newbook=false,$over=false,$author=false,$paginator=1,$nid=null){
		$api=model('common/api');
		$api->api_url=true;
		$list=$api->get_novel($id,$order,$limit,$pos,$time,$newbook,$over,$author,$paginator,$nid);
		return json(["code"=>1,"data"=>$list]);
	}

	public function content($id){
		$api=model('common/api');
		$api->api_url=true;
		$book=$api->novel_detail($id);
		if($book){
			$book['reader_url']=$api->novel_reader_url($book['id']);
			$api->hits($id,'novel');
		}else{
            $error = $api->getError();
            return json(["code"=>0,"msg"=>empty($error) ? '未找到该小说！' : $error]);
		}
		return json(["code"=>1,"data"=>$book]);
	}

	public function chapter_list($id, $order='id asc', $limit='', $page=false){
		$chapter_list=model('common/api')->get_chapter_list($id, $order, $limit, $page);
		return json(["code"=>1,"data"=>$chapter_list]);
	}

	public function chapter($id,$key){
		$api=model('common/api');
		$api->api_url=true;
		$chapter=$api->get_chapter($id,$key);
		if($chapter){
			return json(["code"=>1,"data"=>$chapter]);
		}else{
			return json(["code"=>0,"msg"=>"未找到该章节！"]);
		}
		
	}

	public function digg($id,$digg){
        $return=model('common/api')->digg($id,'novel',$digg);
        if($return){
           return json(["code"=>1,"msg"=>$digg.'+1']);
        }else{
           return json(["code"=>0,"msg"=>"请不要重复操作！"]);
        }
    }

}