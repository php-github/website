<div class="row">
<?php
$intRow = 0;
foreach ($arrRoom['room_list'] as $arrItem) {
    $strLiveIn = $arrItem['liveIn'] ? '直播中' : '直播已结束';
echo <<<EOF
        <div class="col-md-4">
            <div class="thumbnail">
                <a href="/room/{$arrItem['_id']}" class="thumbnail" style="text-decoration:none" target="_blank">
                    <img alt="300x200" src="{$arrItem['coverUrl']}" />
                </a>
                <div class="caption">
                    <a href="/room/{$arrItem['_id']}" target="_blank">
                        <h4 style="text-align:center">{$arrItem['name']}</h4>
                    </a>
                    <span class="label label-primary">{$arrItem['local']}</span>
                    <span class="label label-info">{$strLiveIn}</span>
                    <span class="label label-primary">{$arrItem['roomType']}</span>
                    <span class="label label-info">{$arrItem['source']}</span>
                    <p></p>
                </div>
            </div>
        </div>
EOF;
$intRow++;
        //每3个重计算栅格
        if (0 == $intRow % 3) {
echo <<<EOF
    <div class="clearfix"></div>
EOF;
        }
}
?>
</div>
