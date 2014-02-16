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
    $strHead = <<<EOF
<style type="text/css">
body{
    background-image: url({$arrRoom['backgroundUrl']});
?>
    background-attachment:fixed;
    background-position: center top;
}</style>
EOF;
    $strTitle = "{$arrRoom['name']}";
    $strDesc = "{$arrRoom['name']}";
    $strKeywords = "{$arrRoom['name']}";
require(TEMPLATE_PATH . '/head.tpl');
require(TEMPLATE_PATH . '/navbar.tpl');
?>
<body>
<?php
$strTitle = urlencode($arrRoom['name']);
$strLiveIn = $arrRoom['liveIn'] ? '直播中' : '直播已结束';
//<span class="label label-info">{$arrRoom['beginDate']}</span>
//<span class="label label-success"><span class="glyphicon glyphicon-thumbs-up"></span> 10000</span>
//<span class="label label-default"><span class="glyphicon glyphicon-thumbs-down"></span> 20</span>
//<span class="label label-danger"><span class="glyphicon glyphicon glyphicon-eye-open"></span> 1000</span>
echo <<<EOF
<div class="container bs-docs-container">
    <div class="row">
        <div class="col-xs-12 col-sm-9">
            <div data-spy="affix">
                <div class="alert navbar-inverse transparent">
                    <h4 style="text-align:center"><span style="color:#F00">{$arrRoom['name']}</span></h4>
                    <span class="label label-primary">{$arrRoom['local']}</span>
                    <span class="label label-info">{$strLiveIn}</span>
                </div>
                <iframe src="{$arrRoom['flvUrl']}" width="480" height="360"></iframe>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">

            <ul class="nav nav-pills nav-stacked" style="max-width: 260px;">
                <li class="active">
                    <a href="#"><span class="badge pull-right">42</span>Home</a>
                </li>
                <li><a href="#">Profile</a></li>
                <li><a href="#"><span class="badge pull-right">3</span>Messages</a></li>
            </ul>
        </div>
    </div>
</div>





        <!-- JiaThis Button BEGIN -->
        <script type="text/javascript">
            var jiathis_config = {data_track_clickback:'true'};
        </script>
        <script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?move=0&amp;uid=1892463" charset="utf-8"></script>
        <!-- JiaThis Button END -->
        
<nav class="navbar navbar-inverse navbar-fixed-bottom transparent" role="navigation">
</nav>
EOF;
?>
<?php require(TEMPLATE_PATH . '/footer.tpl');?>
</body>
