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
use think\facade\Config;
use net\Gather;

class SaveChapter extends Controller{

	public function  index($id,$key){
		ignore_user_abort(true);
		if(!Config::get('web.chapter_txt')){
			return false;
		}
        $data_update=false;
        $novel_word=0;
        $map[] = ['status','=',1];
        $map[] = ['id','=',$id];
		$chapter_data=Db::name('novel_chapter')->field('id,chapter,novel_id,collect_id')->where($map)->find();
    	if($chapter_data){
    		$chapter_data['chapter']=model('common/api')->decompress_chapter($chapter_data['chapter']);
    		$chapter_data['chapter']=json_decode($chapter_data['chapter'],true);
			$chapter_data_keys = array_keys($chapter_data['chapter']);
	    	$chapter_data_keys_num = array_search($key,$chapter_data_keys);
	    	$get_save_chapter=array_slice($chapter_data_keys,$chapter_data_keys_num+1,Config::get('web.chapter_preloading_num'));
            foreach ($get_save_chapter as $value) {
                $chapter=$chapter_data['chapter'][$value];
                if($chapter['auto']==1){
                    $getchapter=model('common/union_chapter')->get_chapter($chapter['reurl']);
                    if(!empty($getchapter['content'])){
                        $word=mb_strlen($getchapter['content']);
                        if($word>500 && Config::get('web.chapter_txt')){
                            model('common/api')->set_chapter_content($chapter['path'],$getchapter['content']);
                            $chapter_data['chapter'][$value]['auto']=0;
                            $chapter_data['chapter'][$value]['word']=$word;
                            $chapter_data['chapter'][$value]['intro']=$getchapter['intro'];
                            $data_update=true;
                        }
                    }
                }elseif($chapter['auto']==2){
                    $getchapter=Gather::get_chapter_content($chapter_data['collect_id'],$chapter['reurl']);
                    if($getchapter!==false){
                        $word=mb_strlen($getchapter['chapter_content']);
                        if($word>500 && Config::get('web.chapter_txt')){
                            model('common/api')->set_chapter_content($chapter['path'],$getchapter['chapter_content']);
                            $chapter_data['chapter'][$value]['auto']=0;
                            $chapter_data['chapter'][$value]['word']=$word;
                            if(!empty($getchapter['chapter_title'])){
                                $chapter_data['chapter'][$value]['title']=$getchapter['chapter_title'];
                            }
                            $data_update=true;
                            $novel_word+=$word-$chapter['word'];
                        }
                    }
                }
            }
            if($data_update){
                if($novel_word!==0){
                    $novel_data=['word'=>Db::raw('word+'.($word-$chapter['word']))];
                    Db::name('novel')->where(['id'=>$chapter_data['novel_id']])->update($novel_data);
                }
                $chapter_data['chapter']=json_encode($chapter_data['chapter']);
                $chapter_data['chapter']=model('common/api')->compress_chapter($chapter_data['chapter']);
                Db::name('novel_chapter')->update($chapter_data);
            }
            
    	}
	}
}