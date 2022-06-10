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
use think\Db;
use think\facade\Request;
use think\facade\Config;
use think\facade\Env;
use think\facade\Cache;
use org\File;
use app\admin\validate\Novel as NovelValidate;

class Novel extends Model{

    protected $autoWriteTimestamp = true;
    protected $auto = ['position'];
    protected $insert = ['status'=>1];

    public function getCategoryTextAttr($value,$data){
        return model('common/api')->get_category($data['category'],'title');
    }

    public function getSerializeTextAttr($value,$data){
        $serialize = [0=>'连载',1=>'完结'];
        return $serialize[$data['serialize']];
    }

    public function getCollectAttr($value,$data){
        $chapter=Db::name('novel_chapter')->where(['novel_id'=>$data['id']])->field('collect_id')->find();
        return model('collect')->info($chapter['collect_id']);
    }

    public function setPositionAttr($value){
        if(!is_array($value)){
            return 0;
        }else{
            $pos = 0;
            foreach ($value as $key=>$value){
                $pos += $value;
            }
            return $pos;
        }
    }

	public function info($id){
		$map['id'] = $id;
    	$info=Novel::where($map)->find();
		return $info;
	}

    public function lists($extra=[]){
        $map = [];
        if(Request::param('category')){
            $map[]  = ['category','=',Request::param('category')];
        }
        $serialize=Request::param('serialize');
        if(isset($serialize)){
            $map[]  = ['serialize','=',Request::param('serialize')];
        }
        if(Request::param('keywords')){
            $map[]  = ['title','like','%'.Request::param('keywords').'%'];
        }
        if(Request::param('position')){
            $map[] = ['position','exp',Db::raw('& '.Request::param('position').' = '.Request::param('position'))];
        }
        $status=Request::param('status');
        if(isset($status)){
            $map[] = ['status','=',$status];
        }
        if(Request::param('order')){
            $order = Request::param('order');
            if(strstr($order,'+')){
                $order=str_replace('+',' ',$order);
            }
        }else{
            $order = 'update_time desc';
        }
        if(isset($extra)){
            $map=array_merge($map,$extra);
        }
        $list=Novel::where($map)->order($order)->paginate(config('web.list_rows'))->each(function($item, $key){
            $item->comment_count = Db::name('comment')->where(['type'=>'novel','mid'=>$item->id,'pid'=>0])->count('id');
        });
        return $list;
    }

	public function edit($data,$type){
        $data_link=[];
        $validate = new NovelValidate;
        if (!$validate->scene($type)->check($data)) {
            $this->error=$validate->getError();
            return false;
        }
        $Novel = new Novel();
        if(empty($data['id'])){
            $result = $Novel->allowField(true)->save($data);
            rm_cache();
            $data_link[]=url('home/novel/index',['id'=>$Novel->id],true,true);
            model('common/DataOperation')->after('add','novel',$data_link);
        }else{
            if($type=='position' || $type=='category'){
                $result = $Novel->allowField(true)->isUpdate(true)->isAutoWriteTimestamp(false)->save($data);
            }else{
                $pic=Novel::where('id',$data['id'])->value('pic');
                if($pic!=$data['pic'] && strpos($pic,'://')===false){
                    File::unlink(".".$pic);
                }
                $result = $Novel->allowField(true)->isUpdate(true)->save($data);
            }
            rm_cache($data['id']);
            $data_link[]=url('home/novel/index',['id'=>$data['id']],true,true);
            model('common/DataOperation')->after('edit','novel',$data_link);
        }
        if(false === $result){
            $this->error=$Novel->getError();
            return false;
        }
        return $result;
    }

    public function edit_field($data){
        $id=$data['id'];
        unset($data['id']);
        $result = Novel::whereIn('id', $id)->update($data);
        if(false === $result){
            $this->error=Novel::getError();
            return false;
        }
        return $result;
    }

    public function del($id){
        $map = ['id' => $id];
        $data = Novel::field('id,pic')->where($map)->select();
        $addons_name = Cache::remember('addons_storage',function(){
            $map = ['status'=>1,'group'=>'storage'];
            return Db::name('Addons')->where($map)->value('name');
        });
        if($addons_name){
            $addons_class = get_addon_class($addons_name);
            if(class_exists($addons_class)){
                $addon = new $addons_class();
            }
        }
        foreach ($data as $value) {
            if(!filter_var($value['pic'],FILTER_VALIDATE_URL)){
                File::unlink(".".$value['pic']);
            }
            if($addons_name){
                $chapter=DB::name('novel_chapter')->where(['novel_id'=>$value['id']])->value('chapter');
                $chapter=model('common/api')->decompress_chapter($chapter);
                $chapter=json_decode($chapter,true);
                if($chapter){
                    $path=array_column($chapter,'path');
                    $addon->unlink($path);
                }
            }else{
                del_dir_file(Env::get('runtime_path').'txt'.DIRECTORY_SEPARATOR.$value['id'],true);
            }
            rm_cache($value['id']);
        }
        $result = Novel::where($map)->delete();
        Db::name('bookshelf')->where(['novel_id'=>$id])->delete();
        DB::name('novel_chapter')->where(['novel_id'=>$id])->delete();
        DB::name('comment')->where(['mid'=>$id,'type'=>'novel'])->delete();
        if(false === $result){
            $this->error=Novel::getError();
            return false;
        }else{
            return $result;
        }
    }
}