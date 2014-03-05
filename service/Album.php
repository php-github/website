<?php
namespace service;
use Tofu\Model;
class Album extends \Tofu\Service
{
    public function mGetAlbumCountById($arrId)
    {
        $objAlbum = new Model('club', 'album');
        $arrCount = array();
        foreach ($arrId as $strId) {
            $intCount = $objAlbum->find(array('_id' => $strId), array(''));
            $arrCount[$strId] = intval($intCount);
        }
        $arrReturn = array(
            'count' => $arrCount,
                );
        return $arrReturn;
    }

}
