<div class="panel panel-primary transparent">
    <div class="panel-heading">
        <h3 class="panel-title">歌曲</h3>
    </div>
    <div class="panel-body">
        <ul class="list-group">
<?php
foreach ($arrMusic['allMusic'] as $arrItem) {
echo <<<EOF
            <li class="list-group-item">
                <span>{$arrItem['name']}&nbsp</span>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="150" height="20" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab">
    <param name="movie" value="/static/js/singlemp3player.swf?file={$arrItem['url']}&backColor=990000&frontColor=ddddff&repeatPlay=false&songVolume=100" />
    <param name="wmode" value="transparent" />
    <embed wmode="transparent" width="150" height="20" src="/static/js/singlemp3player.swf?file={$arrItem['url']}&backColor=990000&frontColor=ddddff&repeatPlay=false&songVolume=100" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
            </li>
EOF;
}
?>
        </ul>
    </div>
</div>
