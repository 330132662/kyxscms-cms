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
use think\Db;
use think\facade\Request;
use think\facade\Cache;

class Bookshelf extends Model
{
    protected $autoWriteTimestamp = true;

	public function info($id){
		$map=['status'=>1,'id'=>$id];
		$data=Bookshelf::where($map)->find();
		return $data;
	}

    public function lists($id=UID,$limit=10,$simple=false){
        $data=Bookshelf::where('user_id',$id)->order('update_time desc')->paginate($limit,$simple);
        if($data){
            foreach ($data as $k=>$v){
                $novel=model('common/api')->novel_detail($v['novel_id']);
                if($novel !== false){
                    $data[$k]['book']=$novel;
                    if($v['chapter_id']){
                        $data[$k]['reader_url']=url('home/chapter/index',['id'=>$v['chapter_id'],'key'=>$v['chapter_key']]);
                    }else{
                        $chapter=Db::name('novel_chapter')->field('id,chapter')->where(['novel_id'=>$v['novel_id']])->find();
                        if($chapter){
                            $chapter['chapter']=model('common/api')->decompress_chapter($chapter['chapter']);
                            $chapter['chapter']=json_decode($chapter['chapter'],true);
                            $data[$k]['reader_url']=url('home/chapter/index',['id'=>$chapter['id'],'key'=>key($chapter['chapter'])]);
                        }else{
                            $data[$k]['reader_url']=url('home/novel/index',['id'=>$data['novel_id']]);
                        }
                    }
                }else{
                    unset($data[$k]);
                }
            }
            return $data;
        }
    }

    public function add($user_id,$novel_id){
        if($this->check($novel_id)){
            $this->error='已经在书架中了！';
            return false;
        }
        $bookshelf_count = Bookshelf::where('user_id',$user_id)->count('id');
        $user=model('user')->get_info($user_id,'exp');
        if($user['json']['bookshelf']!=1){
            $this->error=$user['group'].'用户不允许使用书架！';
            return false;
        }
        if($bookshelf_count>=$user['json']['bookshelf_num']){
            $this->error=$user['group'].'用户书架书籍数量不能超过'.$user['json']['bookshelf_num'].'本！';
            return false;
        }
        $data=['user_id'=>$user_id,'novel_id'=>$novel_id];
        $result = Bookshelf::create($data);
        Db::name('novel')->where(['id'=>$novel_id])->setInc('favorites');
        if(false === $result){
            $this->error=Bookshelf::getError();
            return false;
        }else{
            $addons_name = Cache::remember('addons_author',function(){
                $map = ['status'=>1,'group'=>'author'];
                return Db::name('Addons')->where($map)->value('name');
            });
            if($addons_name){
                $addons_class = get_addon_class($addons_name);
                if(class_exists($addons_class)){
                    $addon = new $addons_class();
                    $addon->setinc($novel_id,'favorites');
                }
            }
            return $result;
        }
    }

    public function chapter_update($novel_id,$chapter_id,$chapter_key){
        if(UID){
            Bookshelf::where(['user_id'=>UID,'novel_id'=>$novel_id])->update(['chapter_id'=>$chapter_id,'chapter_key'=>$chapter_key,'update_time'=>time()]);
        }
    }

    public function check($novel_id){
        if(UID){
            return Bookshelf::where(['user_id'=>UID,'novel_id'=>$novel_id])->value('id');
        }
        return false;
    }

    public function del(){
        $id = array_unique((array)Request::param('id'));
        if(empty($id)){
            $this->error='请选择要操作的数据!';
            return false;
        }
        $map = ['id' => $id];
        $result = Bookshelf::where($map)->delete();
        if(false === $result){
            $this->error=Bookshelf::getError();
            return false;
        }else{
            return $result;
        }
    }
}