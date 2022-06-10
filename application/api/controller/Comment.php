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
use think\Db;

class Comment extends ApiBase{

	public function add(){
    	if(UID){
    		$data=$this->request->post();
    		$Comment=model('common/comment');
    		if($Comment->comment_add($data)){
	    		return json(['code'=>1,'msg'=>'评论发送成功！']);
	    	}else{
	    		return json(['code'=>0,'msg'=>$Comment->getError()]);
	    	}
    	}else{
    		return json(['code'=>0,'msg'=>'请先登录！']);
    	}
    }

    public function up($id){
    	if(!cookie('comment_up_'.$id)){
    		cookie('comment_up_'.$id,true);
    		Db::name('Comment')->where(['id'=>$id])->setInc('up');
    		return json(['code'=>1,'msg'=>'+1']);
    	}else{
    		return json(['code'=>0,'msg'=>'请不要重复点赞！']);
    	}
    }

	public function lists($id,$pid=0,$type='novel',$order='up desc,id desc',$limit=20){
		$map['status']=1;
        $map['type']=$type;
        $map['mid']=$id;
        $map['pid']=$pid;
        $list=Db::name('comment')->where($map)->order($order)->paginate($limit);
        $user=model('user/user');
        $user->api_url=true;
        foreach ($list as $key => $value) {
        	$value['user']=$user->get_info($value['uid'],'username,headimgurl,exp,integral');
        	$value['replyCount']=Db::name('comment')->where(['mid'=>$id,'pid'=>$value['id'],'status'=>1])->count();
        	$list[$key]=$value;
        }
		return json(['code'=>1,'data'=>$list]);
	}

	public function content($id){
		$map['status']=1;
		$map['id']=$id;
		$content=Db::name('comment')->where($map)->find();
		$user=model('user/user');
        $user->api_url=true;
		$content['user']=$user->get_info($content['uid'],'username,headimgurl,exp,integral');
		$content['replyCount']=Db::name('comment')->where(['mid'=>$content['mid'],'pid'=>$content['id'],'status'=>1])->count();
		return json(['code'=>1,'data'=>$content]);
	}
}