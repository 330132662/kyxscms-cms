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
use think\Controller;
use think\facade\Env;
use think\Db;

class Base extends Controller{

    protected function initialize(){
        $this->view->config(['cache_path'=>Env::get('runtime_path').'temp'.DIRECTORY_SEPARATOR.$this->request->module().DIRECTORY_SEPARATOR]);
        if(!in_array(strtolower($this->request->controller()."/".$this->request->action()),['index/login','index/verify','index/welcome','log/index'])){
            $data = [
                'member_id' => is_login('admin'),
                'time' => $this->request->time(),
                'ip'   => $this->request->ip(1),
                'method' => $this->request->method(),
                'controller' => $this->request->controller(),
                'action' => $this->request->action(),
                'param' => json_encode($this->request->param())
            ];
            try{
                Db::name('member_log')->insert($data);
            }catch(\Exception $e){
            }
        }
    }

    /**
     * 对数据表中的单行或多行记录执行修改 GET参数id为数字或逗号分隔的数字
     *
     * @param string $model 模型名称,供M函数使用的参数
     * @param array  $data  修改的数据
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'')
     */
    final protected function editRow ( $model ,$data, $where , $msg ){
        $id    = array_unique((array)$this->request->param('id'));
        $id    = is_array($id) ? implode(',',$id) : $id;
        $fields = Db::name($model)->getTableFields();
        if(in_array('id',$fields) && !empty($id)){
            $where = array_merge( ['id' => ['in', $id ]] ,(array)$where );
        }
        $msg   = array_merge( ['success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>''] , (array)$msg );
        if( Db::name($model)->where($where)->update($data)!==false ) {
            return $this->success($msg['success'],$msg['url']);
        }else{
            $this->error($msg['error'],$msg['url']);
        }
    }

    /**
     * 禁用条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的 where()方法的参数
     * @param array  $msg   执行正确和错误的消息,可以设置四个元素 array('success'=>'','error'=>'', 'url'=>'')
     *
     */
    protected function forbid ( $model , $where = [] , $msg = ['success'=>'状态禁用成功！','error'=>'状态禁用失败！']){
        $data    =  ['status' => 0];
        return $this->editRow( $model , $data, $where, $msg);
    }

    /**
     * 恢复条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'')
     */
    protected function resume (  $model , $where = [] , $msg = ['success'=>'状态恢复成功！','error'=>'状态恢复失败！']){
        $data    =  ['status' => 1];
        return $this->editRow( $model , $data, $where, $msg);
    }
}
