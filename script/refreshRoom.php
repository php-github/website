<?php
require(__DIR__ . '/../webroot/init.php');

//抓6.cn首页房间信息
$url = 'http://www.6.cn';
$cip = $forward = getRandIp();
$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, $url);  
curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
    "X-FORWARDED-FOR:$forward",  
    "CLIENT-IP:$cip"  
));  
curl_setopt($ch, CURLOPT_REFERER, '');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
$contents = curl_exec($ch);
curl_close($ch);

preg_match_all('/"rid":"([\d]*?)","pic":"([^ ]*?)".*?"isRecommend":(\d*?),"rtype":"([^ ]*?)".*?,"count":"(\d*?)",/', $contents, $arrMatch);
$arrRid = $arrMatch[1];
$arrPic = $arrMatch[2];
$arrIsRecommend = $arrMatch[3];
$arrRType = $arrMatch[4];
$arrCount = $arrMatch[5];
foreach ($arrRid as $strKey => $strRid) {
    $arrRooms[$strRid] = array();
    $arrRooms[$strRid]['pic'] = str_replace("\/", "/", $arrPic[$strKey]);
    $arrRooms[$strRid]['is_recommend'] = $arrIsRecommend[$strKey];
    $arrRooms[$strRid]['rtype'] = $arrRType[$strKey];
    $arrRooms[$strRid]['count'] = $arrCount[$strKey];
    $arrUrls[] = "http://v.6.cn/$strRid";
}

$arrReturn = multi_curl($arrUrls, 'getRoomInfo');
$arrReturn = array_filter($arrReturn);

foreach ($arrReturn as $strKey => $arrItem) {
    $arrRooms[$arrItem['room_id']] = array_merge($arrRooms[$arrItem['room_id']], $arrItem);
}

$objRoomService = new service\Room('club', 'room', CONF_PATH . '/room.dic');
//file_put_contents('r', serialize($arrRooms));
$ctime = date('Y-m-d H:i:s', strtotime('now'));
//$arrRooms = unserialize(file_get_contents('r'));
foreach ($arrRooms as $arrItem) {
    $objRoomService = new service\Room('club', 'room', CONF_PATH . '/room.dic');
    $bolUpdate = true;
    $arrData = array();
    try {
        $arrFindRoom = $objRoomService->execute('findByRoomId', (array('roomId' => $arrItem['room_id'])));
        $arrData['_id'] = $arrFindRoom['_id'];
    } catch (exception $e) {
        $bolUpdate = false;
    }

    $arrData['name'] = $arrItem['name'];
    $arrData['flvUrl'] = $arrItem['flv_url'];
    $arrData['backgroundUrl'] = $arrItem['background_url'];
    $arrData['beginDate'] = $arrItem['begin_date'];
    $arrData['local'] = $arrItem['local'];
    $arrData['coverUrl'] = $arrItem['pic'];
    $arrData['weighting'] = $arrItem['count'] + $arrItem['is_recommend'] * 1000;
    $arrData['ctime'] = $ctime;
    $arrData['roomId'] = $arrItem['room_id'];
    $arrData['roomType'] = rtype2roomType($arrItem['rtype']);
    $arrData['source'] = '6.cn';
    $arrData['keywords'] = array();
    $arrData['liveIn'] = 1;
    try {
        if ($bolUpdate) {
            $objRoomService->execute('updateById', $arrData);
            user_error('update sucess');
        } else {
            $objRoomService->execute('add', $arrData);
            user_error('add sucess');
        }
    } catch (exception $e) {
        user_error('add fail ' . $e->getMessage() . json_encode($arrData), E_USER_ERROR);
    }
}
$objRoomService->execute('updateNoLiveInRoom', array('ctime' => $ctime));

//file_put_contents('rooms', var_export($arrRooms, true));

//file_put_contents('rooms_ser', serialize($arrRooms));



function multi_curl($arrUrls, $callback)
{
    $intChunk = 5;
    $arrUrls = array_chunk($arrUrls, $intChunk);
    $arrReturn = array();
    foreach ($arrUrls as $arrItem) {
        sleep(5);
        $arrReturn = array_merge($arrReturn, classic_curl($arrItem, $callback));
    }
    /*
    //retry
    $intRetry = 2;
    do {
        $arrRetry = array();
        foreach ($arrReturn as $strKey => $arrItem) {
            if (false === $arrItem) {
                $arrRetry[] = $strKey;
            }
        }
        $arrRetry = array_chunk($arrRetry, $intChunk);
        foreach ($arrRetry as $arrItem) {
            $arrReturn = array_merge($arrReturn, classic_curl($arrItem, $callback));
        }
    } while(--$intRetry);
    */
    return $arrReturn;
}

function classic_curl($urls, $callback) {
    $queue = curl_multi_init();
    $map = array();

    foreach ($urls as $url) {
        // create cURL resources
        $cip = $forward = getRandIp();
        $ch = curl_init();
        $ua = getRandUa();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        curl_setopt($ch, CURLOPT_REFERER, 'http://www.6.cn');
        // 设置选项，浏览器信息
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
            "X-FORWARDED-FOR:$forward",  
            "CLIENT-IP:$cip"  
        ));  

        // add handle
        curl_multi_add_handle($queue, $ch);
        $map[$url] = $ch;
    }

    $active = null;

    // execute the handles
    do {
        $mrc = curl_multi_exec($queue, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);

    while ($active > 0 && $mrc == CURLM_OK) {
        if ($a = curl_multi_select($queue, 5) != -1) {
            do {
                $mrc = curl_multi_exec($queue, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }

    $responses = array();
    foreach ($map as $url=>$ch) {
        $contents = curl_multi_getcontent($ch);
        $responses[$url] = false;
        if (empty($contents)) {
            user_error("$url get contents fail");
        } else {
            $responses[$url] = call_user_func($callback, $contents);
        }
        curl_multi_remove_handle($queue, $ch);
        curl_close($ch);
    }

    curl_multi_close($queue);
    return $responses;
}


function getRoomInfo($contents)
{
    if (!$contents) {
        user_error('content is empty', E_USER_ERROR);
        return false;
    }
    //kaibo
    preg_match('/<span class="time" id="live_title_2013">开播(.*)<\/span>/', $contents, $arrMatch);
    $strKaiBo = '';
    if (isset($arrMatch[1])) {
        $strKaiBo = trim($arrMatch[1]);
    }
    if (!$strKaiBo) {
        user_error('room is not opened');
        return false;
    }

    //room id
    preg_match('/roomid: (\d*?),/', $contents, $arrMatch);
    $strRoomid = $arrMatch[1];


    //flv url
    preg_match('/"flvtitle":"([^ "]*)"/', $contents, $arrMatch);
    $strFlvTitle = $arrMatch[1];
    preg_match('/Fplayer: \'([^ \']*)\'/', $contents, $arrMatch);
    $strPlayer = $arrMatch[1];
    if ($strFlvTitle && $strPlayer) {
        $strFlvUrl = "http://v.6.cn$strPlayer?fileName=$strFlvTitle";
    } else {
        user_error('flv url is missing', E_USER_ERROR);
        return false;
    }

    //master name
    preg_match('/masterName:\'([^ \']*)\'/', $contents, $arrMatch);
    $strMasterName = '';
    if (isset($arrMatch[1])) {
        $strMasterName = $arrMatch[1];
    }

    //background
    preg_match('/background-image: url\(([^ ]*)\)/', $contents, $arrMatch);
    $strBackground = '';
    if (isset($arrMatch[1])) {
        $strBackground = $arrMatch[1];
    }

    //local
    preg_match('/<span class="local"><i class="fixpng"><\/i>(.*?)<\/span>/', $contents, $arrMatch);
    $strLocal = '';
    if (isset($arrMatch[1])) {
        $strLocal = trim($arrMatch[1]);
    }

    return array('room_id' =>$strRoomid, 'name' => $strMasterName, 'flv_url' => $strFlvUrl, 'background_url' => $strBackground, 'begin_date' => $strKaiBo, 'local' => $strLocal);
}

function rtype2roomType($strRtype) {
    $arrConf = array('r10' => '炽星', 'r5' => '超星', 'r4' => '巨星', 'r1' => '明星', 'r2' => '红人', 'fu1' => '舞区', 'u2' => 'MC', 'u6' => '乐吧', 'u3' => '聊吧', 'jc' => '综艺');
    return $arrConf[$strRtype] ? : '未知';
}

function getRandIp(){
    $ip_long = array(
        array('607649792', '608174079'), //36.56.0.0-36.63.255.255
        array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
        array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
        array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
        array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
        array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
        array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
        array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
        array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
        array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
        );
    $rand_key = mt_rand(0, 9);
    return long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
}

function getRandUa(){
    $arrUa = array(
            'Mozilla/5.0 (X11; U; Linux i686; it; rv:1.9.3a1pre) Gecko/20091019 Minefield/3.7a1pre', 'Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.9.3a4pre) Gecko/20100402 Minefield/3.7a4pre', 
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.53 Safari/525.19',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.36 Safari/525.19',
            'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/7.0.540.0 Safari/534.10',
            'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/534.4 (KHTML, like Gecko) Chrome/6.0.481.0 Safari/534.4',
            'Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.86 Safari/533.4',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/532.2 (KHTML, like Gecko) Chrome/4.0.223.3 Safari/532.2',
            'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/4.0.201.1 Safari/532.0',
            'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/3.0.195.27 Safari/532.0',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/530.5 (KHTML, like Gecko) Chrome/2.0.173.1 Safari/530.5',
            'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.558.0 Safari/534.10',
            'Mozilla/5.0 (X11; U; Linux x86_64; en-US) AppleWebKit/540.0 (KHTML,like Gecko) Chrome/9.1.0.0 Safari/540.0',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.14 (KHTML, like Gecko) Chrome/9.0.600.0 Safari/534.14',
            'Mozilla/5.0 (X11; U; Windows NT 6; en-US) AppleWebKit/534.12 (KHTML, like Gecko) Chrome/9.0.587.0 Safari/534.12',
            'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.13 (KHTML, like Gecko) Chrome/9.0.597.0 Safari/534.13',
            'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.11 Safari/534.16',
            'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/534.20 (KHTML, like Gecko) Chrome/11.0.672.2 Safari/534.20',
            'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.792.0 Safari/535.1',
            'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.872.0 Safari/535.2',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.36 Safari/535.7',
            'Mozilla/5.0 (Windows NT 6.0; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.66 Safari/535.11',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.45 Safari/535.19',
            'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/535.24 (KHTML, like Gecko) Chrome/19.0.1055.1 Safari/535.24',
            'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1090.0 Safari/536.6',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/22.0.1207.1 Safari/537.1',
            'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.15 (KHTML, like Gecko) Chrome/24.0.1295.0 Safari/537.15',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1467.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1623.0 Safari/537.36',
            'Mozilla/4.0 (compatible; MSIE 5.0; Windows NT;)',
            'Mozilla/4.0 (Windows; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)',
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; GTB5; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506; InfoPath.2; OfficeLiveConnector.1.3; OfficeLivePatch.0.0)',
            'Mozilla/4.0 (Mozilla/4.0; MSIE 7.0; Windows NT 5.1; FDM; SV1; .NET CLR 3.0.04506.30)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0; .NET CLR 2.0.50727)',
            'Mozilla/4.0 (compatible; MSIE 5.0b1; Mac_PowerPC)',
            'Mozilla/2.0 (compatible; MSIE 4.0; Windows 98)',
            'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT)',
            'Mozilla/4.0 (compatible; MSIE 5.23; Mac_PowerPC)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; GTB6; Ant.com Toolbar 1.6; MSIECrawler)',
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 1.1.4322; InfoPath.2; .NET CLR 3.5.21022; .NET CLR 3.5.30729; MS-RTC LM 8; OfficeLiveConnector.1.4; OfficeLivePatch.1.3; .NET CLR 3.0.30729)',
            'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)',
            'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; Media Center PC 6.0; InfoPath.3; MS-RTC LM 8; Zune 4.7)',
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)',
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)',
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Win64; x64; Trident/6.0)',
            'Mozilla/5.0 (IE 11.0; Windows NT 6.3; Trident/7.0; .NET4.0E; .NET4.0C; rv:11.0) like Gecko',
        );
    $key = array_rand($arrUa, 1);
    return $arrUa[$key];
}
?>
