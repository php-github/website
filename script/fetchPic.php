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
    foreach ($arrTotalPic as $strItem) {
        $strKey = crc32($strItem);
        if (!isset($arrAlbum['allAlbum'][$strKey])) {
            $arrAlbum['allAlbum'][$strKey]['source'] = $strItem;
            $arrAlbum['allAlbum'][$strKey]['thumb'] = str_replace('.jpg', '_s.jpg', $strItem);
        }
    }
    if (!isset($arrAlbum['allAlbum'])) {
        user_error("rid = {$arrItem['roomId']} uid = {$arrItem['uid']} allAlbum is empty");
        continue;
    }
    if (!isset($arrAlbum['auditedAlbum'])) {
        $arrAlbum['auditedAlbum'] = array();
    }
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
            $arrPicInfo[] = $arrItem['sourcepath'];
        }
        $arrTotalPic = array_merge($arrTotalPic, $arrPicInfo);
    } while (count($arrPicInfo));
    return $arrTotalPic;
}
