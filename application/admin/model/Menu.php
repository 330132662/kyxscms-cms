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

namespace app\admin\model;
use think\Model;
use think\facade\Cache;

class Menu extends Model {

	public function getMenus()
    {
        return Cache::remember('admin_menu',function(){
			// 获取主菜单
            $where  =  ['pid'=>0,'hide'=>0];
            $menus['main']  =   Menu::where($where)->order('sort asc')->select();
            $menus['child'] = []; //设置子节点
            //高亮主菜单
            foreach ($menus['main'] as $key => $item) {
                $groups = Menu::where([['group','<>',''],['pid','=',$item['id']]])->order('sort asc')->column("group");
                foreach ($groups as $g) {
                    $map = ['group'=>$g,'pid'=>$item['id'],'hide'=>0];
                    $child = Menu::where($map)->field('id,pid,title,url,icon,tip')->order('sort asc')->select();
                    $menus['child'][$key][$g] = $child->toArray();
                }
            }
            return $menus;
		});    
    }
}