<?php
$intWidth = $intWidth ? : 480;
$intHeight = $intHeight ? : 360;
echo <<<EOF
<div>
<script type="text/javascript">document.write('<iframe width="{$intWidth}" height="{$intHeight}" frameborder="0" scrolling="no" src="{$strFlvUrl}&mute=0&autoplay=true&uid={$arrRoom['6_uid']}&isRecordBtn=true"></iframe>')</script>
</div>
<button type="button" class="btn btn-default pull-right"><a href="{$strFlvUrl}&mute=0&autoplay=true&uid={$arrRoom['6_uid']}&isRecordBtn=true">点击全屏观看</button>
EOF;
?>
