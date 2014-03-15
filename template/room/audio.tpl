<?php
require(TEMPLATE_PATH . '/head.tpl');
?>
<body>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="25" height="20" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab">
    <param name="movie" value="/static/js/singlemp3player.swf?file=http://i.6.cn/live/mp3/45/13/2418785713451074011345.mp3&backColor=990000&frontColor=ddddff&repeatPlay=true&songVolume=30" />
    <param name="wmode" value="transparent" />
    <embed wmode="transparent" width="25" height="20" src="/static/js/singlemp3player.swf?file=http://i.6.cn/live/mp3/45/13/2418785713451074011345.mp3&backColor=990000&frontColor=ddddff&repeatPlay=true&songVolume=30" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
</body>
<?php
require(TEMPLATE_PATH . '/footer.tpl');
?>
