<div class="bs-sidebar hidden-print affix" role="complementary">
    <ul class="nav nav-stacked nav-pills list-group">
<?php
foreach ($arrRoomCount['count'] as $strKey => $intCount) {
    $strActive = $strKey == $strRoomType ? 'active' : '';
    if ($intCount) {
echo <<<EOF
        <li class="{$strActive}"><a href="/?roomType={$strKey}"><span class="glyphicon glyphicon-star pull-left"></span><span class="badge pull-right">{$intCount}</span><strong>&nbsp;&nbsp;{$strKey}&nbsp;&nbsp;</strong></a></li>
EOF;
    } else {
echo <<<EOF
        <li class="disabled {$strActive}"><a href="#"><span class="glyphicon glyphicon-star pull-left"></span><span class="badge pull-right">{$intCount}</span><strong>&nbsp;&nbsp;{$strKey}&nbsp;&nbsp;</strong></a></li>
EOF;
    }
}
?>
    </ul>
</div>
