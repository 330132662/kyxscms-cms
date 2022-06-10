<?php
namespace app\admin\model;
use think\Model;

class Log extends Model{

    protected $name = 'member_log';

    public function getMemberNameAttr($value,$data){
        $MemberModel=model('Member');
        $member=$MemberModel->info($data['member_id']);
        return $member['username'];
    }

    public function lists(){
        $list = Log::order('id desc')->paginate(config('web.list_rows'));
        return $list;
    }
}