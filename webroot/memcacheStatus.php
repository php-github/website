<?php
/**
 * Copyright © 2012 云智慧（北京）科技有限公司 <http://www.jiankongbao.com/>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING
 * BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. 
 *
 **/

/**
 * 使用说明：
 * (1) 此程序是用来收集memcached status的数据
 * (2) 正确运行此程序需要你的环境已经安装memcached module, 你可以参考http://code.google.com/p/memcached/
 * (3) 你需要配置两个参数： $host 和 $port 代表memcached 所在服务器的host和port
 * (4)
 *
 **/

$jkb_version='1.0';

$host = '127.0.0.1';

$port = 11211;



$memcache_obj = new Memcache;
$retval = $memcache_obj->connect($host, $port);
if (!$retval) die('Could not connect memcached.');
$status = $memcache_obj->getExtendedStats();

$memcache_obj->close();

header('Content-Type: text/plain; charset=UTF-8');

if (empty($status) || !is_array($status)) {
    echo 'cannot connect to memcached';
    exit;
}

$status[$host.':'.$port]['jkb_version']=$jkb_version;

echo json_encode($status[$host.':'.$port]);


