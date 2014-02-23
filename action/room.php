<?php
//数据层
$objRoomService = new service\Room('club', 'room', CONF_PATH . '/room.dic');
try {
    $arrRoom = $objRoomService->execute('findById', array('id' => $_REQUEST['id']), 60);
} catch (exception $e) {
    $arrRoom['name'] = '';
    $arrRoom['backgroundUrl'] = '';
}
$bdPic = $arrRoom['coverUrl'];
$bdText = "#看秀场# 我正在看{$arrRoom['name']}的直播，一级棒，速速围观!";
$bdDesc = "来自{$arrRoom['local']}的{$arrRoom['roomType']}秀场";
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
            <div class="page-header">
                <?php echo "<h1 align='center'><strong>{$arrRoom['name']}</strong></h1>"; ?>
            </div>
            <div id="live" align="center">
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
