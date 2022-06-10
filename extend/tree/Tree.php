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
namespace tree;

class Tree{
    static public $treeList = []; //存放无限分类结果如果一页面有多个无限分类可以使用 Tool::$treeList = array(); 清空
    /**
     * 无限级分类
     * @access public 
     * @param Array $data     //数据库里获取的结果集 
     * @param Int $pid             
     * @param Int $count       //第几级分类
     * @return Array $treeList   
     */
    static public function tree($data,$pid = 0,$count = 0,$char="|-") {
        foreach ($data as $key => $value){
            if($value['pid']==$pid){
				$value['count'] = $count;
				$value['html'] = str_repeat($char,$count);
                self::$treeList []=$value;
                unset($data[$key]);
                self::tree($data,$value['id'],$count+1,$char);
            } 
        }
        return self::$treeList ;
    }
    
 }
?>