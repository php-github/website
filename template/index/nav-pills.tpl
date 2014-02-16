<ul class="nav nav-pills nav-stacked transparent affix" role="complementary">
<?php
foreach ($arrRoomCount['count'] as $strKey => $intCount) {
    $strActive = $strKey == $strRoomType ? 'active' : '';
    if ($intCount) {
echo <<<EOF
        <li class="{$strActive}"><a href="/?roomType={$strKey}"><span class="glyphicon glyphicon-star pull-left"></span><span class="badge pull-right">{$intCount}</span><strong>&nbsp;&nbsp;{$strKey}&nbsp;&nbsp;</strong></a>
EOF;
    } else {
echo <<<EOF
        <li class="{$strActive}"><a href="#" class="disabled"><span class="glyphicon glyphicon-star pull-left"></span><span class="badge pull-right">{$intCount}</span><strong>&nbsp;&nbsp;{$strKey}&nbsp;&nbsp;</strong></a>
EOF;
    }
}
?>
</ul>
