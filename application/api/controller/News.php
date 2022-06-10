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

class News extends ApiBase{

	public function category($cid=false,$type=1,$filter=false){
		$category=model('api/api')->category($cid,$type,$filter);
		return json(["code"=>1,"data"=>$category]);
	}

	public function lists($cid=false,$order='update_time desc',$limit=20,$pos=false,$time=false,$paginator=1,$nid=null){
		$api=model('common/api');
		$api->api_url=true;
		$list=$api->get_news($cid,$order,$limit,$pos,$time,$paginator,$nid);
		return json(["code"=>1,"data"=>$list]);
	}

	public function content($id){
		$api=model('common/api');
		$api->api_url=true;
		$news=$api->news_detail($id);
		$api->hits($id,'news');
		return json(["code"=>1,"data"=>$news]);
	}

	public function digg($id,$digg){
        $return=model('common/api')->digg($id,'news',$digg);
        if($return){
           return json(["code"=>1,"msg"=>$digg.'+1']);
        }else{
           return json(["code"=>0,"msg"=>"请不要重复操作！"]);
        }
    }
}