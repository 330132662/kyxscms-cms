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
use think\Db;
use think\Model;
use think\facade\Request;
use org\File;
use app\admin\validate\News as NewsValidate;

class News extends Model{

    protected $autoWriteTimestamp = true;
    protected $auto = ['position'];
    protected $insert = ['status'=>1];

    public function getCategoryTextAttr($value,$data){
        return model('common/api')->get_category($data['category'],'title');
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
    	$info=News::where($map)->find();
		return $info;
	}

    public function lists(){
         $map = [];
        if(Request::param('category')){
            $map[]  = ['category','=',Request::param('category')];
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
        $list=News::where($map)->order($order)->paginate(config('web.list_rows'))->each(function($item, $key){
            $item->comment_count = Db::name('comment')->where(['type'=>'news','mid'=>$item->id,'pid'=>0])->count('id');
        });
        return $list;
    }

	public function edit($data,$type){
        $data_link=[];
        $validate = new NewsValidate;
        if (!$validate->scene($type)->check($data)) {
            $this->error=$validate->getError();
            return false;
        }
        $News = new News();
        if(empty($data['id'])){
            $result = $News->allowField(true)->save($data);
            rm_cache(NUll,'news');
            $data_link[]=url('home/news/index',['id'=>$News->id],true,true);
            model('common/DataOperation')->after('add','news',$data_link);
        }else{
            if($type=='position'){
                $result = $News->allowField(true)->isUpdate(true)->isAutoWriteTimestamp(false)->save($data);
            }else{
                $pic=News::where('id',$data['id'])->value('pic');
                if($pic!=$data['pic'] && isset($pic)){
                    File::unlink(".".$pic);
                }
                $result = $News->allowField(true)->isUpdate(true)->save($data);
            }
            rm_cache($data['id'],'news');
            $data_link[]=url('home/news/index',['id'=>$data['id']],true,true);
            model('common/DataOperation')->after('edit','news',$data_link);
        }
        if(false === $result){
            $this->error=$News->getError();
            return false;
        }
        return $result;
    }

    public function edit_field($data){
        $id=$data['id'];
        unset($data['id']);
        $result = News::whereIn('id', $id)->update($data);
        if(false === $result){
            $this->error=News::getError();
            return false;
        }
        return $result;
    }

    public function del($id){
        $map = ['id' => $id];
        $data = News::field('id,pic')->where($map)->select();
        foreach ($data as $value) {
            rm_cache($value['id'],'news');
            if(!filter_var($value['pic'],FILTER_VALIDATE_URL)){
                File::unlink(".".$value['pic']);
            }
        }
        $result = News::where($map)->delete();
        DB::name('comment')->where(['mid'=>$id,'type'=>'news'])->delete();
        if(false === $result){
            $this->error=News::getError();
            return false;
        }else{
            return $result;
        }
    }
}