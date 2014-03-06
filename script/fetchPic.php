<?php
require(__DIR__ . '/../webroot/init.php');
$objRoomService = new service\Room('club', 'room', CONF_PATH . '/room.dic');
$arrRoom = $objRoomService->execute('getYesterdayAndTodayLiveRoom');
foreach ($arrRoom as $arrItem) {
    if (!isset($arrItem['uid'])) {
        continue;
    }
    $arrTotalPic = fetchPic($arrItem['uid']);
    $objAlbumService = new Tofu\service('club', 'album', CONF_PATH . '/album.dic');
    try {
        $arrAlbum = $objAlbumService->execute('findById', array('id' => $arrItem['_id']));
    } catch (RuntimeException $e) {
        $arrAlbum = array('_id' => $arrItem['_id']);
    }
    foreach ($arrTotalPic as $arrItem) {
        $strKey = crc32($arrItem['source']);
        $arrAlbum['allAlbum'][$strKey]['source'] = $arrItem['source'];
        $arrAlbum['allAlbum'][$strKey]['thumb'] = str_replace(array('.jpg', '.png'), array('_s.jpg', '_s.png'), $arrItem['source']);
        $arrAlbum['allAlbum'][$strKey]['weighting'] = $arrItem['weighting'];
        $arrAlbum['allAlbum'][$strKey]['title'] = $arrItem['title'];
    }
    if (!isset($arrAlbum['allAlbum'])) {
        user_error("rid = {$arrItem['roomId']} uid = {$arrItem['uid']} allAlbum is empty");
        continue;
    }
    if (!isset($arrAlbum['auditedAlbum'])) {
        $arrAlbum['auditedAlbum'] = array();
    }
    $arrAlbum['allAlbumCount'] = count($arrAlbum['allAlbum']);
    try {
        $objAlbumService->execute('updateById', $arrAlbum);
    } catch (exception $e) {
        user_error($e->getMessage());
    }
    user_error($arrItem['_id'] . "fetch ok");
}

function fetchPic($intUid)
{
    $arrTotalPic = array();
    $intPage = 0;
    do {
        ++$intPage;
        $url = "http://v.6.cn/profile/photo.php?act=list&format=json&uid={$intUid}&page=$intPage";
        $response = Tofu\Thief::mCurl(array($url));
        $list = json_decode($response[$url], true);
        $picInfo = $list['content']['picInfoAry'];
        $arrPicInfo = array();
        foreach ($picInfo as $arrItem) {
            $arrTmp['source'] = $arrItem['sourcepath'];
            $arrTmp['weighting'] = $arrItem['visit_num'];
            $arrTmp['title'] = $arrItem['title'];
            $arrPicInfo[] = $arrTmp;
        }
        $arrTotalPic = array_merge($arrTotalPic, $arrPicInfo);
    } while (count($arrPicInfo));
    return $arrTotalPic;
}
