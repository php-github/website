<?php
namespace service;
use Tofu\Model;
class Room extends \Tofu\Service
{
    public function getIndexRoom($arrArguments)
    {
        $objClub = new Model('club', 'room');
        $arrArguments['skip'] = max($arrArguments['skip'], 0);
        //多取1个，判断是否还有更多
        $intLimit = $arrArguments['limit'] + 1;
        $arrMaxTime = array_pop(iterator_to_array($objClub->find(array(), array('ctime' => 1))->sort(array('ctime' => -1))->limit(1)));
        $arrRoomList = iterator_to_array($objClub->find(array('ctime' => $arrMaxTime['ctime'], 'liveIn' => 1, 'roomType' => $arrArguments['roomType']))->sort(array('weighting' => -1))->skip($arrArguments['skip'])->limit($intLimit));

        $bolHasPrev = true;
        $bolHasMore = false;
        if (0 === $arrArguments['skip']) {
            $bolHasPrev = false;
        }
        if ($intLimit === count($arrRoomList)) {
            $bolHasMore = true;
            //最后一个用来判断是否还有更多，不显示给用户，丢弃
            array_pop($arrRoomList);
        }
        $arrReturn = array(
            'has_more' => $bolHasMore,
            'has_prev' => $bolHasPrev,
            'room_list' => $arrRoomList,
            'skip' => $arrArguments['skip'],
            'limit' => $arrArguments['limit'],
            'roomType' => $arrArguments['roomType'],
                );
        return $arrReturn;
    }

    public function findByRoomId($arrArguments)
    {
        $objClub = new Model('club', 'room');
        $objModel = $objClub->findFirst(array('roomId' => $arrArguments['roomId']));
        if ($objModel->isEmpty()) {
            throw new \RuntimeException("can not find room by id={$arrArguments['roomId']}");
        }
        return $objModel->toArray();
    }

    public function updateNoLiveInRoom($arrArguments)
    {
        $objClub = new Model('club', 'room');
        $arrUnLiveInRoom = $objClub->findAll(array('ctime' => array('$lt' => $arrArguments['ctime']), 'liveIn' => 1));
        foreach ($arrUnLiveInRoom as $objModel) {
            $objModel->liveIn = 0;
            $objModel->save();
            user_error("{$objModel->roomId} update no liveIn");
        }
    }

    public function getRoomTypeName()
    {
        return array('炽星', '超星', '巨星', '明星', '红人', '舞区', 'MC', '乐吧', '聊吧', '综艺');
    }

    public function getRoomTypeCount()
    {
        $objClub = new Model('club', 'room');
        $arrMaxTime = array_pop(iterator_to_array($objClub->find(array(), array('ctime' => 1))->sort(array('ctime' => -1))->limit(1)));

        $arrRoomType = $this->getRoomTypeName();
        foreach ($arrRoomType as $strRoomType) {
            $intCount = $objClub->find(array('ctime' => $arrMaxTime['ctime'], 'liveIn' => 1, 'roomType' => $strRoomType))->count();
            $arrCount[$strRoomType] = intval($intCount);
        }

        $arrReturn = array(
            'count' => $arrCount,
                );
        return $arrReturn;
    }
}
