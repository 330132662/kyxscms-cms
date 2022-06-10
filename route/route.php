<?php
use think\Db;
use think\facade\Cache;

try {
	$route=Cache::get('route_data');
   	if(!$route){
		$data=Db::name('route')->select();
		foreach ($data as $key => $value) {
			$val=json_decode($value['value'],true);
			if($value['group']){
				$route['['.$value['group'].']'][$value['name']]=[$val[0],isset($val[1]) ? $val[1] : []];
			}else{
				$route[$value['name']]=[$val[0],isset($val[1]) ? $val[1] : []];
			}
		}
		Cache::set('route_data',$route);
	}
	return $route;
}catch(Exception $e){}