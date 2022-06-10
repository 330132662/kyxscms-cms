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
use think\Controller;
use think\Db;
use think\facade\Cookie;

class Crontab extends Controller{

	public function index(){
		ignore_user_abort(true);
		if($this->request->isGet()){
			$crontab=Db::name('crontab')->where(['status'=>1])->whereTime('run_time', '<=', time())->lock(true)->select();
			foreach ($crontab as $key => $value) {
				Db::name('crontab')->where(['id'=>$value['id']])->update(['run_time'=>time()+$value['interval']]);
				$content=json_decode($value['content'],true);
				switch ($value['type']) {
					case 1:
						$layer=$content['layer'];
						controller($content['url'])->$layer($content['vars']);
						break;
					case 2:
						$layer=$content['layer'];
						model($content['url'])->$layer($content['vars']);
						break;
					default:
						break;
				}
			}

		}
	}

}