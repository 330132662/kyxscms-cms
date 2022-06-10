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
namespace app\admin\behavior;
use think\Request;
/**
 * 检测用户有没有登录
 */
class LoginStatus
{
    public function run(Request $request,$params){
        $allowUrl = ['admin/index/login',
			         'admin/index/logout',
                     'admin/index/verify'
			        ];
        $visit = strtolower($request->module()."/".$request->controller()."/".$request->action());
        if(empty(is_login('admin')) && !in_array($visit,$allowUrl)){
	        if($request->isAjax()){
	        	echo json_encode(['status'=>0,'msg'=>'对不起，您还没有登录，请先登录']);
	        }else{
	        	echo "<script>window.top.location.href='".url('admin/index/login')."';</script>";
	        }
            exit();
        }
    }
}