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

namespace app\admin\controller;
use think\Db;
use net\Gather;

class NovelChapter extends Base
{

    public function index($id){
        $NovelChapter=model('NovelChapter');
        $list = $NovelChapter->lists($id);
        $this->assign('list', $list['chapter']);
        $this->assign('id', $list['id']);
        $this->assign('meta_title','章节列表');
        return $this->fetch();
    }

    public function chapter($id,$key){
        $NovelChapter=model('NovelChapter');
        $info=$NovelChapter->info($id,$key);
        if($info['auto']==1){
            $getchapter=model('common/union_chapter')->get_chapter($info['reurl']);
            if($getchapter['content']){
                $info['issued']=1;
                $info['content']=$getchapter['content'];
                $info['intro']=$getchapter['intro'];
                $NovelChapter->edit($info);
            }else{
                $info['content']=model('common/union_chapter')->getError();
            }
        }elseif($info['auto']==2){
            $getchapter=Gather::get_chapter_content($info['collect_id'],$info['reurl']);
            unset($info['reurl']);
            if($getchapter!==false){
                $info['title']=$getchapter['chapter_title'];
                $info['content']=$getchapter['chapter_content'];
                $NovelChapter->edit($info);
            }else{
                $info['content']='获取章节内容错误，请检查采集规则！';
            }
        }
        return $this->success('章节获取成功！',null,$info);
    }
	
	public function edit($id){
		$NovelChapter=model('NovelChapter');
        $data = $this->request->post();
		$res = $NovelChapter->edit($data);
        if($res  !== false){
            if(empty($this->request->param('id'))){
                return $this->success('章节添加成功！',null,['id'=>$res['id'],'url'=>url('chapter',['id'=>$res['id'],'key'=>$res['key']])]);
            }else{
                if(empty($this->request->param('issued'))){
                    return $this->success('章节保存成功！',null,['id'=>$id,'url'=>url('chapter',['id'=>$id,'key'=>$res])]);
                }else{
                    return $this->success('章节发布成功！',null,['issued'=>1]);
                }
            }
        } else {
            $this->error($NovelChapter->getError());
        }
	}

    public function import(){
        if($this->request->isPost()){
            $NovelChapter=model('NovelChapter');
            $data = $this->request->post();
            $res = $NovelChapter->import($data);
            if($res  !== false){
                if(!empty($data['type']==='tests')){
                    return $this->success('章节测试！','',$res);
                }else{
                    return $this->success('章节导入成功！');
                }
                
            }else{
                $this->error($NovelChapter->getError());
            }
        }else{
            $this->assign('meta_title','章节导入');
            return $this->fetch();
        }
    }

    public function del(){
        $id = $this->request->param('id');
        $key = array_unique((array)$this->request->param('key'));
        if ( empty($key) ) {
            $this->error('请选择要操作的数据!');
        }
        $NovelChapter=model('NovelChapter');
        $res = $NovelChapter->del($id,$key);
        if($res  !== false){
            $this->success('删除成功');
        } else {
            $this->error($NovelChapter->getError());
        }
    }
}