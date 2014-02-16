<ul class="pager">
<?php
$arrGet = $_GET;
unset($arrGet['skip']);
$strQuery = http_build_query($arrGet);
    if ($bolHasPrev) {
echo <<<EOF
        <li><a href="{$_SERVER['PATH_INFO']}?{$strQuery}&skip={$intPrevSkip}"><span class="glyphicon glyphicon-arrow-left"></span></a></li>
EOF;
    } else {
echo <<<EOF
        <li class="disabled"><a><span class="glyphicon glyphicon-arrow-left"></span></a></li>
EOF;
    }
    if ($bolHasMore) {
echo <<<EOF
        <li><a href="{$_SERVER['PATH_INFO']}?{$strQuery}&skip={$intMoreSkip}"><span class="glyphicon glyphicon-arrow-right"></span></a></li>
EOF;
    } else {
echo <<<EOF
        <li class="disabled"><a><span class="glyphicon glyphicon-arrow-right"></span></a></li>
EOF;
    }
?>
    </ul>
</div>
