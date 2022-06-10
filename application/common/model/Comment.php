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

namespace app\common\model;
use think\Model;
use think\Db;
use think\Validate;
use think\facade\Config;
use think\facade\Cache;

class Comment extends Model {

    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = false;
    protected $auto = ['content'];
    protected $insert = ['status' => 1];

	public function comment_add($data){
        $validate   = Validate::make(['content'=>'require'],['content.require'=>'评论内容不能为空']);
        $result = $validate->check($data);
        if(!$result) {
            $this->error=$validate->getError();
            return false;
        }
		$data['uid']=UID;
        $user=model('user/user')->get_info($data['uid'],'exp');
        if($user['json']['comment']!=1){
            $this->error=$user['group'].'该用户组不允许评论！';
            return false;
        }
        $result=Comment::save($data);
        if(false === $result){
            $this->error=Comment::getError();
            return false;
        }
        Db::name('user')->where('id',$data['uid'])->inc('exp',$user['json']['comment_exp'])->inc('integral',$user['json']['comment_integral'])->update();
        if(empty($data['pid'])){
            $addons_name = Cache::remember('addons_author',function(){
                $map = ['status'=>1,'group'=>'author'];
                return Db::name('Addons')->where($map)->value('name');
            });
            if($addons_name){
                $addons_class = get_addon_class($addons_name);
                if(class_exists($addons_class)){
                    $addon = new $addons_class();
                    $addon->setinc($data['mid'],'comment');
                }
            }
        }
        
        return $result;
    }

    /**
     * 获取分类树，指定分类则返回指定分类极其子分类，不指定则返回所有分类树
     * @param  integer $id    分类ID
     * @param  boolean $field 查询字段
     * @return array          分类树
     */
    public function get_tree($mid, $type='novel', $id = 0, $field = true, $api = false){
        $map['status']=1;
        $map['type']=$type;
        $map['mid']=$mid;
        $user=model('user/user');
        $user->api_url=$api;
        /* 获取所有分类 */
        $list = Comment::field($field)->where($map)->order('up desc,id desc')->select();
        foreach ($list as $key => $value) {
        	$list[$key]['user']=$user->get_info($value['uid'],'username,headimgurl,exp,integral');
        }
        $list = list_to_tree($list->toArray(), $pk = 'id', $pid = 'pid', $child = '_', $root = $id);
        return $list;
    }

    protected function setContentAttr($value){
        $str = htmlspecialchars($value);
        $comment_key = preg_split('/[\r\n]+/', trim(Config::get('web.comment_key'), "\r\n"));
        $str = str_replace($comment_key, '***', $str);
        return $str;
    }
}
