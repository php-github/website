<?php
require(__DIR__ . '/../webroot/init.php');
$objAlbum = new Tofu\Model('club', 'album');
$arrAlbum = $objAlbum->find();
foreach ($arrAlbum as $arrItem) {
    foreach ($arrItem['allAlbum'] as $arrItem2) {
        $strExt = pathinfo($arrItem2['source'], PATHINFO_EXTENSION);
        $strSourceFile = md5($arrItem2['source']) . ".$strExt";
        is_dir(__DIR__."/album/{$arrItem['_id']}") || mkdir(__DIR__."/album/{$arrItem['_id']}");
        $strFile = __DIR__."/album/{$arrItem['_id']}/{$strSourceFile}";
        if (is_readable($strFile)) {
            continue;
        }
        $response = Tofu\Thief::mCurl(array($arrItem2['source']));
        $strContents = $response[$arrItem2['source']];
        if ($strContents) {
            file_put_contents($strFile, $strContents);
        }
    }
    user_error("{$arrItem['_id']} ok");
}
