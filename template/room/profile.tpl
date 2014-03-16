<div class="panel panel-primary transparent">
    <div class="panel-heading">资料</div>
<?php
echo <<<EOF
    <div class="panel-body">
        <div class="list-group">
        <li class="list-group-item">
            <h4 class="list-group-item-heading">出生年份:</h4>
            <p class="list-group-item-text">{$arrProfile['birth_year']}</p>
        </li>
        <li class="list-group-item">
            <h4 class="list-group-item-heading">生日:</h4>
            <p class="list-group-item-text">{$arrProfile['birth_day']}</p>
        </li>
        <li class="list-group-item">
            <h4 class="list-group-item-heading">星座:</h4>
            <p class="list-group-item-text">{$arrProfile['constellation']}</p>
        </li>
        <li class="list-group-item">
            <h4 class="list-group-item-heading">家乡:</h4>
            <p class="list-group-item-text">{$arrProfile['home']}</p>
        </li>
        <li class="list-group-item">
            <h4 class="list-group-item-heading">血型:</h4>
            <p class="list-group-item-text">{$arrProfile['bloob']}</p>
        </li>
        <li class="list-group-item">
            <h4 class="list-group-item-heading">身高:</h4>
            <p class="list-group-item-text">{$arrProfile['height']}</p>
        </li>
        <li class="list-group-item">
            <h4 class="list-group-item-heading">体重:</h4>
            <p class="list-group-item-text">{$arrProfile['weight']}</p>
        </li>
        <li class="list-group-item">
            <h4 class="list-group-item-heading">三围:</h4>
            <p class="list-group-item-text">{$arrProfile['3s']}</p>
        </li>
        <li class="list-group-item">
            <h4 class="list-group-item-heading">发色:</h4>
            <p class="list-group-item-text">{$arrProfile['hair']}</p>
        </li>
        <li class="list-group-item">
            <h4 class="list-group-item-heading">鞋码:</h4>
            <p class="list-group-item-text">{$arrProfile['shoes']}</p>
        </li>
        <li class="list-group-item">
            <h4 class="list-group-item-heading">特长:</h4>
            <p class="list-group-item-text">{$arrProfile['specialty']}</p>
        </li>
        <li class="list-group-item">
            <h4 class="list-group-item-heading">职业:</h4>
            <p class="list-group-item-text">{$arrProfile['job']}</p>
        </li>
        <li class="list-group-item">
            <h4 class="list-group-item-heading">个人签名:</h4>
            <p class="list-group-item-text">{$arrProfile['signature']}</p>
        </li>
        <li class="list-group-item">
            <h4 class="list-group-item-heading">座右铭:</h4>
            <p class="list-group-item-text">{$arrProfile['motto']}</p>
        </li>
        </div>
    </div>
EOF;
?>
</div>
