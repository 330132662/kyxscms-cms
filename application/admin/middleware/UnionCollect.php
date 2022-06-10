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

namespace app\admin\middleware;

class UnionCollect
{
    public function handle($request, \Closure $next)
    {
	    switch ($request->param('model')) {
            case 'id':
	            if(is_array($request->param('id'))){
	            	$request->id = implode(',',$request->param('id'));
	            }else{
	            	$request->id = $request->param('id',null);
	            }
                break;
            case 'today':
                 $request->time = $request->param('model');
                break;
        }
        return $next($request);
    }
}