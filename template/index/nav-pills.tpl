<div class="bs-sidebar hidden-print affix list-group" role="complementary">
<?php
foreach ($arrRoomCount['count'] as $strKey => $intCount) {
    $strActive = $strKey == $strRoomType ? 'active' : '';
    if ($intCount) {
echo <<<EOF
        <a href="/?roomType={$strKey}" class="list-group-item {$strActive}"><span class="glyphicon glyphicon-star pull-left"></span><span class="badge pull-right">{$intCount}</span><strong>&nbsp;&nbsp;{$strKey}&nbsp;&nbsp;</strong></a>
EOF;
    } else {
echo <<<EOF
        <a href="#" class="disabled list-group-item {$strActive}"><span class="glyphicon glyphicon-star pull-left"></span><span class="badge pull-right">{$intCount}</span><strong>&nbsp;&nbsp;{$strKey}&nbsp;&nbsp;</strong></a>
EOF;
    }
}
?>
</div>
