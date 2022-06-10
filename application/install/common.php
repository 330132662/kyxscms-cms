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

function check_dirfile(){
    $dirList = [
        'addons',
        'config',
        'template',
        'uploads',
        'runtime',
        'application',
        'public'
    ];
    $list = [];
    foreach ($dirList as $file) {
        $writeable = is_writeable(think\facade\Env::get('root_path') . $file);
        $list[] = [
            "dir" => $file,
            "writeable" => $writeable
        ];
    }
    return $list;
}

function check_func(){
    $funList = [
        [
            'name' => 'Mbstring字符处理',
            'func' => 1,
            'value' => 'mb_substr',
            'must' => 0
        ],
        [
            'name' => 'Curl传输',
            'func' => 1,
            'value' => 'curl_init',
            'must' => 0
        ],
        [
            'name' => 'Pdo数据库',
            'func' => 0,
            'value' => 'Pdo',
            'must' => 0
        ],
        [
            'name' => 'php_fileinfo',
            'func' => 1,
            'value' => 'finfo_open',
            'must' => 0
        ],
        [
            'name' => '文件读写',
            'func' => 1,
            'value' => 'file_get_contents',
            'must' => 0
        ],
        [
            'name' => 'Gd图像处理',
            'func' => 1,
            'value' => 'imagecreate',
            'must' => 1
        ],
        [
            'name' => 'Gzip压缩',
            'func' => 1,
            'value' => 'ob_gzhandler',
            'must' => 1
        ],
    ];

    foreach ($funList as $key => $vo) {
        if($vo['func']) {
            $status = function_exists($vo['value']);
        }else{
            $status = class_exists($vo['value']);
        }
        $funList[$key]['status'] = $status;
    }
    return $funList;
}

function check_data($data){
    $rule = [
        'host'  => 'require',
        'port'   => 'require|number',
        'dbname' => 'require',
        'username' => 'require',
        'prefix' => 'require',
        'admin_user' => 'require',
        'admin_pw' => 'require'
    ];
    $msg = [
        'host.require' => '请填写数据库地址！',
        'port'     => '请填写数据库端口！',
        'dbname.require'   => '请填写数据库名称！',
        'username.require'  => '请填写数据库用户名！',
        'prefix.require'        => '请填写数据表前缀！',
        'admin_user.require'        => '管理员账号不能为空！',
        'admin_pw.require'        => '管理员密码不能为空！'
    ];
    $validate  = think\Validate::make($rule,$msg);
    if(!$validate->check($data)){
        show_msg($validate->getError(), true);
        install_stop();
    }
}

function check_db($data){
    if(!class_exists('Pdo')){
        show_msg('安装失败，请确保您的环境支持pdo扩展！', true);
        install_stop();
    }
    $dsn = "mysql:host=" . $data['host'] . ";port=" . $data['port'] . ";charset=utf8";
    $link = null;
    try {
        $link = new \PDO($dsn, $data['username'], $data['password']);
    } catch (\PDOException $e) {
        show_msg('数据库连接失败，请检查连接信息是否正确或者数据库是否存在！错误信息:' . $e->getMessage(), true);
        install_stop();
    }
    if (!$link) {
        show_msg('数据库连接失败，请检查连接信息是否正确或者数据库是否存在！', true);
        install_stop();
    }
    $link->exec("SET NAMES UTF-8");
    // 创建数据库并选中
    if (!$link->query("use ".$data['dbname'])) {
        $create_sql = 'CREATE DATABASE IF NOT EXISTS '.$data['dbname'].' DEFAULT CHARACTER SET utf8;';
        $link->exec($create_sql) or show_msg('创建数据库失败',true);
        $link->query("use ".$data['dbname']);
    }
    return $link;
}

/**
 * 写入配置文件
 * @param  array $config 配置信息
 */
function write_config($data){
    $config_tpl = think\facade\Env::get('module_path').'data/conf.tpl';
    $config=org\File::read($config_tpl);
    foreach ($data as $name => $value) {
        $config = str_replace("[{$name}]", $value, $config);
    }
    $config_file = think\facade\Env::get('config_path').'database.php';
    $status = org\File::put($config_file, $config);
    if ($status) {
        show_msg('配置数据库信息完成...');
    } else {
        show_msg('配置数据库信息失败！', true);
        install_stop();
    }  
}

/**
 * 创建数据表
 * @param  resource $db 数据库连接资源
 */
function create_tables($db, $prefix = ''){
    //读取SQL文件
    $sql_file = think\facade\Env::get('module_path').'data/install.sql';
    if (!is_file($sql_file)) {
        show_msg('数据库文件不存在');
        install_stop();
    }
    $sql = org\File::read($sql_file);
    $sql = str_replace(["\r"," `ky_"], ["\n"," `{$prefix}"], $sql);
    $sql = explode(";\n", $sql);
    //开始安装
    show_msg('开始安装数据库...');
    foreach ($sql as $value) {
        $value = trim($value);
        if(empty($value)) continue;
        if(substr($value, 0, 12) == 'CREATE TABLE') {
            $name = preg_replace("/^CREATE TABLE `(\w+)` .*/s", "\\1", $value);
            $msg  = "创建数据表{$name}";
            if(false !== $db->exec($value)){
                show_msg($msg . '...成功');
            } else {
                show_msg($msg . '...失败！',true);
                install_stop();
            }
        } else {
            $db->exec($value);
        }
    }
}

function register_administrator($db, $data){
    show_msg('开始注册管理员帐号...');
    $sql = "INSERT INTO `[PREFIX]member` VALUES " . "(1, '[NAME]', '[PASS]', 0, '[IP]', '[TIME]', 1)";
    $password = think_ucenter_md5($data['admin_pw']);
    $sql = str_replace(
        ['[PREFIX]', '[NAME]', '[PASS]','[TIME]', '[IP]'],
        [$data['prefix'], $data['admin_user'], $password, time(),think\facade\Request::ip(1)],
        $sql);
    //执行sql
    $rst=$db->exec($sql);
    if ($rst !== false) {
        show_msg('管理员帐号注册成功!');
    }else{
        show_msg('管理员帐号注册失败!',true);
        install_stop();
    }
}


function show_msg($msg, $error = 0) {
    usleep(200000);
    echo "<script>msg(\"$msg\", $error);</script>\n";
}

function complete($msg) {
    echo "<script>complete(\"$msg\");</script>";
}

function install_stop() {
    exit;
}