<?php
require(__DIR__ . '/../webroot/init.php');
$objRoomService = new service\Room('club', 'room', CONF_PATH . '/room.dic');
$arrRoom = $objRoomService->execute('getYesterdayAndTodayLiveRoom');
foreach ($arrRoom as $arrItem) {
    if (!isset($arrItem['roomId'])) {
        continue;
    }
    //$arrItem['roomId'] = 7778;
    $arrFetch = fetchProfile($arrItem['roomId']);
    $objProfileService = new Tofu\service('club', 'profile', CONF_PATH . '/profile.dic');
    try {
        $arrProfile = $objProfileService->execute('findById', array('id' => $arrItem['_id']));
    } catch (RuntimeException $e) {
        $arrProfile = array('_id' => $arrItem['_id']);
    }
    foreach ($arrFetch as $strKey => $strItem) {
        if ('last_live' == $strKey) {
            $arrProfile[$strKey][$strItem] = $strItem;
        } else {
            $arrProfile[$strKey] = $strItem;
        }
    }
    try {
        $objProfileService->execute('updateById', $arrProfile);
    } catch (exception $e) {
        user_error($e->getMessage());
    }
    user_error($arrProfile['_id'] . " fetch ok");
}

function fetchProfile($intRoomId)
{
    $url = "http://v.6.cn/profile/record.php?rid={$intRoomId}";
    $response = Tofu\Thief::mCurl(array($url));
    preg_match('/<td><span class="num date">(.*?)<\/span>上次直播<\/td>/', $response[$url], $arrMatch);
    $intLastLive = strtotime($arrMatch[1]);
    $arrProfile['last_live'] = $intLastLive;
    preg_match('/<dt>出生年份<\/dt>[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $strBirthyear = $arrMatch[1];
    $arrProfile['birth_year'] = $strBirthyear;
    preg_match('/<dt>生日<\/dt>[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $strBirthday = $arrMatch[1];
    $arrProfile['birth_day'] = $strBirthday;
    preg_match('/<dt>星座<\/dt>[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $strConstellation = $arrMatch[1];
    $arrProfile['constellation'] = $strConstellation;
    preg_match('/<dt>家乡<\/dt>[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $strHome = $arrMatch[1];
    $arrProfile['home'] = $strHome;
    preg_match('/<dt>血型<\/dt>[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $strBlood = $arrMatch[1];
    $arrProfile['bloob'] = $strBlood;
    preg_match('/<dt>身高[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $strHeight = $arrMatch[1];
    $arrProfile['height'] = $strHeight;
    preg_match('/<dt>体重[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $strWeight = $arrMatch[1];
    $arrProfile['weight'] = $strWeight;
    preg_match('/<dt>三围[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $str3s = $arrMatch[1];
    $arrProfile['3s'] = $str3s;
    preg_match('/<dt>头发颜色[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $strHair = $arrMatch[1];
    $arrProfile['hair'] = $strHair;
    preg_match('/<dt>鞋码[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $strShoes = $arrMatch[1];
    $arrProfile['shoes'] = $strShoes;
    preg_match('/<dt>特长[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $strSpecialty = $arrMatch[1];
    $arrProfile['specialty'] = $strSpecialty;
    preg_match('/<dt>职业[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $strJob = $arrMatch[1];
    $arrProfile['job'] = $strJob;
    preg_match('/<dt>座右铭[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $strMotto = $arrMatch[1];
    $arrProfile['motto'] = $strMotto;
    preg_match('/<dt>个性签名[\s|\S]*?<p.*?>(.*?)<\/p>/', $response[$url], $arrMatch);
    $strSignature = $arrMatch[1];
    $arrProfile['signature'] = $strSignature;
    return $arrProfile;
}
