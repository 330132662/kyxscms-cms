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
use think\facade\Request;
use think\facade\Cache;
use app\admin\validate\Category as CategoryValidate;

class Category extends Model{

    protected $autoWriteTimestamp = true;
    protected $insert = ['status'=>1];

    public function getTypeTextAttr($value,$data)
    {
        $status = [0=>'小说',1=>'文章',2=>'独立模版',3=>'外链'];
        return $status[$data['type']];
    }

	public function info($id){
		$map['id'] = $id;
    	$info=Category::where($map)->find();
		return $info;
	}

    /**
     * 获取分类树，指定分类则返回指定分类极其子分类，不指定则返回所有分类树
     * @param  integer $id    分类ID
     * @param  boolean $field 查询字段
     * @return array          分类树
     */
    public function getTree($id = 0, $field = true){
        /* 获取当前分类信息 */
        $Tree = new \tree\Tree;
        $Tree::$treeList = [];
        $list = Category::field($field)->order('pid asc,id asc,sort asc')->select();
        return $Tree->tree($list,0,0,'&nbsp;&nbsp;&nbsp;&nbsp;');
    }

	public function edit(){
        $data=Request::post();
        $validate = new CategoryValidate;
        if (!$validate->check($data)) {
            $this->error=$validate->getError();
            return false;
        }
        $Category = new Category();
        if(empty($data['id'])){
            $result = $Category->allowField(true)->save($data);
        }else{
            $result = $Category->allowField(true)->isUpdate(true)->save($data);
        }
        if(false === $result){
            $this->error=$Category->getError();
            return false;
        }
        Cache::rm('category');
        return $result;
    }

    public function del($id){
        $map = ['id' => $id];
        $result = Category::where($map)->delete();
        if(false === $result){
            $this->error=Category::getError();
            return false;
        }else{
            return $result;
        }
    }
}