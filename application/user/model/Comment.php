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

class Comment extends Model
{

    public function getTypeTextAttr($value,$data)
    {
        $status = ['news'=>'文章','novel'=>'小说'];
        return $status[$data['type']];
    }

    public function lists($id=UID,$limit=10,$simple=false,$reply=0){
        $map[]=['uid','=',$id];
        if($reply){
            $map[]=['pid','<>',0];
        }else{
            $map[]=['pid','=',$reply];
        }
        $list=Comment::where($map)->order('id desc')->paginate($limit,$simple);
        if($list){
            foreach ($list as $key => $value) {
                $list[$key]['user']=model('user')->get_info($value['uid'],'username,headimgurl,exp,integral');
                if($value['type']=='news'){
                    $list[$key]['news']=model('common/api')->news_detail($value['mid']);
                }else{
                    $list[$key]['novel']=model('common/api')->novel_detail($value['mid']);
                }
            }
            return $list;
        }
    }
}