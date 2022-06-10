<?php
namespace think;

if(version_compare(PHP_VERSION,'5.6.0','<'))  die('PHP版本过低，最少需要PHP5.6，请升级PHP版本！');
// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');
// 加载框架基础引导文件
require __DIR__ . '/thinkphp/base.php';
// 执行应用并响应
if(!is_file(APP_PATH . 'install/data/install.lock')){
	Container::get('app')->path(APP_PATH)->bind('install')->run()->send();
}else{
	Container::get('app')->path(APP_PATH)->run()->send();
}