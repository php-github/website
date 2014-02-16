<?php
//数据层
$objRoomService = new service\Room('club', 'room', CONF_PATH . '/room.dic');
try {
    $arrRoom = $objRoomService->execute('findById', array('id' => $_REQUEST['id']));
} catch (exception $e) {
}
?>
<?php
//展示层
$strTitle = urlencode($arrRoom['name']);
$strLiveIn = $arrRoom['liveIn'] ? '直播中' : '直播已结束';
    $strHead = <<<EOF
<style type="text/css">
body{
    background-image: url({$arrRoom['backgroundUrl']});
    background-attachment:fixed;
    background-position: center top;
}</style>
EOF;
$strTitle = "{$arrRoom['name']}";
$strDesc = "{$arrRoom['name']}";
$strKeywords = "{$arrRoom['name']}";
require(TEMPLATE_PATH . '/head.tpl');
?>
<body>
<?php
require(TEMPLATE_PATH . '/navbar.tpl');
?>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-2 column">
<?php
require(TEMPLATE_PATH . '/room/nav-pills.tpl');
?>
        </div>
        <div class="col-md-6 column">
            <div id="live">
<?php require(TEMPLATE_PATH . '/room/live.tpl');?>
            </div>
            <div id="duoshuo">
<?php require(TEMPLATE_PATH . '/duoshuo.tpl');?>
            </div>
        </div>
    </div>
</div>
</body>
<?php
require(TEMPLATE_PATH . '/footer.tpl');
?>
