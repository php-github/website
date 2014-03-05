<?php
namespace service;
use Tofu\Model;
class Room extends \Tofu\Service
{
    const RECOMMEND_ROOM_LIMIT = 50;

    public function getIndexRoom($arrArguments)
    {
        $objClub = new Model('club', 'room');
        $arrArguments['skip'] = max($arrArguments['skip'], 0);
        //多取1个，判断是否还有更多
        $intLimit = $arrArguments['limit'] + 1;
        if ('推荐' !== $arrArguments['roomType']) {
            $arrRoomList = iterator_to_array($objClub->find(array('roomType' => $arrArguments['roomType']))->sort(array('liveIn' => -1, 'weighting' => -1))->skip($arrArguments['skip'])->limit($intLimit));
        } else {
            $arrRoomList = iterator_to_array($objClub->find()->sort(array('liveIn' => -1, 'weighting' => -1))->skip($arrArguments['skip'])->limit(self::RECOMMEND_ROOM_LIMIT));
        }

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

    public function getYesterdayAndTodayLiveRoom()
    {
        $objClub = new Model('club', 'room');
        //多取1个，判断是否还有更多
        $arrLiveRoom = iterator_to_array($objClub->find(array('ctime' => array('$gt' => strtotime('-1 day')))));
        return $arrLiveRoom;
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
        return array('推荐', '炽星', '超星', '巨星', '明星', '红人', '舞区', 'MC', '乐吧', '聊吧', '综艺', '视频');
    }

    public function getRoomTypeCount()
    {
        $objClub = new Model('club', 'room');
        $arrRoomType = $this->getRoomTypeName();
        foreach ($arrRoomType as $strRoomType) {
            if ('推荐' !== $strRoomType) {
                $intCount = $objClub->find(array('roomType' => $strRoomType))->count();
            } else {
                $intCount = min($objClub->find()->count(), self::RECOMMEND_ROOM_LIMIT);
            }
            $arrCount[$strRoomType] = intval($intCount);
        }

        $arrReturn = array(
            'count' => $arrCount,
                );
        return $arrReturn;
    }
}
