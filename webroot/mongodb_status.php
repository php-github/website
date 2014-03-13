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
 * (1) 此程序是用来收集mongodb status的数据
 * (2) 你需要配置你的mongodb状态页地址，如http://www.domain.com:11001/_status
 *
 **/

$jkb_version='1.0';

$url='http://127.0.0.1:28017/_status';

$str=@file_get_contents($url);
if (empty($str)) {
    echo 'url error!';
    exit;
}
$json=json_decode($str,true);
if (!isset($json['serverStatus'])) {
    echo 'mongodb status page error!';
    exit;
}
$json['jkb_version'] = $jkb_version;

echo json_encode($json);
