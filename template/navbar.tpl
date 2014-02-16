<?php
$strIndexActive = '/index' == ACTION_KEY ? 'active' : '';
$strRoomActive = '/room' == ACTION_KEY ? 'active' : '';
$strFoundActive = '/found' == ACTION_KEY ? 'active' : '';
$strRankActive = '/rank' == ACTION_KEY ? 'active' : '';
$strMallActive = '/mall' == ACTION_KEY ? 'active' : '';
$strAboutActive = '/about' == ACTION_KEY ? 'active' : '';
$strHelpActive = '/help' == ACTION_KEY ? 'active' : '';
?>
<style type="text/css">
    body { padding-top: 70px; padding-bottom: 70px;}
</style>
<div class="navbar-wrapper">
    <div class="container">
        <div class="nav navbar-inverse transparent navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/">看秀场</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
<?php
echo <<<EOF
                        <li class="{$strIndexActive}"><a href="/" class="navbar-link"><span class="glyphicon glyphicon-home"></span> 首页</a></li>
                        <li class="{$strRoomActive}"><a href="/room" class="navbar-link"><span class="glyphicon glyphicon-hd-video"></span> 秀场</a></li>
                        <li class="{$strFoundActive}"><a href="/found" class="navbar-link"><span class="glyphicon glyphicon-globe"></span> 发现</a></li>
                        <li class="{$strRankActive}"><a href="/rank" class="navbar-link"><span class="glyphicon glyphicon-list"></span> 排行榜</a></li>
                        <li class="{$strMallActive}"><a href="/mall" class="navbar-link"><span class="glyphicon glyphicon-shopping-cart"></span> 商城</a></li>
                        <li class="{$strAboutActive}"><a href="/about" class="navbar-link"><span class="glyphicon glyphicon-info-sign"></span> 关于</a></li>
                        <li class="{$strHelpActive}"><a href="/help" class="navbar-link"><span class="glyphicon glyphicon-question-sign"></span> 帮助</a></li>
EOF;
?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <form class="navbar-form navbar-left" role="search">
                            <div class="form-group">
                                <input type="text" class="form-control" />
                            </div>
                            <button type="submit" class="btn btn-primary">搜索</button>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
