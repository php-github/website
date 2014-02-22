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
            <meta name="description" content="{$strDesc}看秀场 - 美女主播的代言人" />
            <meta name="keywords" content="{$strKeywords}看秀场 - 美女视频,美女直播" /> 
EOF;
?>
            <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
            <style type="text/css">
                .transparent
                {
                    filter:alpha(opacity=80);
                    -moz-opacity:0.8;
                    -khtml-opacity: 0.8;
                    opacity: 0.8;
                }
            </style>
            <script src="http://siteapp.baidu.com/static/webappservice/uaredirect.js" type="text/javascript"></script><script type="text/javascript">uaredirect("http://m.kanxiuchang.com","http://www.kanxiuchang.com");</script>
            <script>var _hmt = _hmt || [];(function() {var hm = document.createElement("script");hm.src = "//hm.baidu.com/hm.js?a77ff727fc9cd39bb892102948525a25";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(hm, s);})();</script>
            <?php echo $strHead;?>
    </head>
