<?php
require(__DIR__ . '/../webroot/init.php');
$objRoomService = new service\Room('club', 'room', CONF_PATH . '/room.dic');
$arrRoom = $objRoomService->execute('getYesterdayAndTodayLiveRoom');
foreach ($arrRoom as $arrItem) {
    if (!isset($arrItem['uid'])) {
        continue;
    }
    $arrTotalMusic = fetchMusic($arrItem['roomId']);
    $objMusicService = new Tofu\service('club', 'music', CONF_PATH . '/music.dic');
    try {
        $arrMusic = $objMusicService->execute('findById', array('id' => $arrItem['_id']));
    } catch (RuntimeException $e) {
        $arrMusic = array('_id' => $arrItem['_id']);
    }
    foreach ($arrTotalMusic as $arrItem) {
        $strKey = crc32($arrItem['url']);
        $arrMusic['allMusic'][$strKey]['url'] = $arrItem['url'];
        $arrMusic['allMusic'][$strKey]['name'] = $arrItem['name'];
    }
    if (!isset($arrMusic['allMusic'])) {
        user_error("rid = {$arrItem['roomId']} uid = {$arrItem['uid']} allMusic is empty");
        continue;
    }
    $arrMusic['allMusicCount'] = count($arrMusic['allMusic']);
    try {
        $objMusicService->execute('updateById', $arrMusic);
    } catch (exception $e) {
        user_error($e->getMessage());
    }
    user_error($arrMusic['_id'] . "fetch ok");
}

function fetchMusic($intRoomId)
{
    $arrTotalMusic = array();
    $intPage = 0;
    $arrReturn = array();
    $url = "http://v.6.cn/profile/audio.php?rid={$intRoomId}";
    $response = Tofu\Thief::mCurl(array($url));
    preg_match_all('/<tr id="aud([0-9]*)">/', $response[$url], $arrMatch);
    foreach ($arrMatch[1] as $intItem) {
        $arrUrl[] = "http://v.6.cn/mp3/playmp3.php?aid={$intItem}&type=undefined";
    }
    $response = Tofu\Thief::mCurl($arrUrl);
    foreach ($response as $strItem) {
        $arrRes = json_decode($strItem, true);
        $arrMp3['url'] = $arrRes['content']['url'];
        $arrMp3['name'] = $arrRes['content']['name'];
        $arrReturn[] = $arrMp3;
    }
    return $arrReturn;
}
