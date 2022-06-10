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

namespace app\home\controller;

use app\common\controller\Base;
use think\facade\Cookie;

class Other extends Base
{
    public function index()
    {
    	Cookie::set('__forward__',$this->request->url());
       	$tpl=$this->request->param('tpl').".html";
       	return $this->fetch($this->home_tplpath.$tpl);
    }
}
