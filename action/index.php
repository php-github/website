<?php
//数据层
$objRoomService = new service\Room('club', 'room', CONF_PATH . '/room.dic');
$arrRoom = $objRoomService->execute('getIndexRoom', $_REQUEST);
$arrRoomCount = $objRoomService->execute('getRoomTypeCount');

$intPrevSkip = max($arrRoom['skip'] - $arrRoom['limit'], 0);
$intMoreSkip = $arrRoom['skip'] + $arrRoom['limit'];
$bolHasMore = $arrRoom['has_more'];
$bolHasPrev = $arrRoom['has_prev'];
$strRoomType = $arrRoom['roomType'];

//展示层
require(TEMPLATE_PATH . '/head.tpl');
?>
<body>
<?php
require(TEMPLATE_PATH . '/navbar.tpl');
require(TEMPLATE_PATH . '/index/carousel.tpl');
?>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-2 column">
<?php
require(TEMPLATE_PATH . '/index/nav-pills.tpl');
?>
        </div>
        <div class="col-md-2 column pull-right">
            <div class="transparent affix" role="complementary">
<?php
require(TEMPLATE_PATH . '/weibo.tpl');
/*
$intWidth = 144;
$intHeight = 108;
$arrFirstRoom = current($arrRoom['room_list']);
$strFlvUrl = $arrFirstRoom['flvUrl'];
$strRoomId = $arrFirstRoom['_id'];
require(TEMPLATE_PATH . '/room/live.tpl');
*/
?>
</a>
            </div>
        </div>
        <div class="col-md-8 column">
<?php
require(TEMPLATE_PATH . '/index/thumbnail.tpl');
require(TEMPLATE_PATH . '/page.tpl');
?>
        </div>
        
    </div>
</div>
</body>
<?php
require(TEMPLATE_PATH . '/footer.tpl');
?>
