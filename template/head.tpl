<?php
if ($strTitle) {
    $strTitle = $strTitle . ' - ';
}
if ($strDesc) {
    $strDesc = $strDesc. ',';
}
if ($strKeywords) {
    $strKeywords = $strKeywords . ',';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--STATUS OK-->
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?php
echo <<<EOF
            <title>{$strTitle}看秀场 - 美女主播的代言人</title>
            <meta name="description" content="{$strDesc}看秀场,美女主播的代言人" />
            <meta name="keywords" content="{$strKeywords}看秀场,美女视频,美女直播" /> 
EOF;
?>
            <link rel="stylesheet" href="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/css/bootstrap.min.css">
            <link rel="stylesheet" href="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/css/bootstrap-theme.min.css">
            <style type="text/css">
                .transparent
                {
                    filter:alpha(opacity=90);
                    -moz-opacity:0.9;
                    -khtml-opacity: 0.9;
                    opacity: 0.9;
                }
            </style>
            <script>var _hmt = _hmt || [];(function() {var hm = document.createElement("script");hm.src = "//hm.baidu.com/hm.js?a77ff727fc9cd39bb892102948525a25";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(hm, s);})();</script>
            <?php echo $strHead;?>
    </head>
