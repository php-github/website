<?php
require(__DIR__ . '/../webroot/init.php');
$objClub = new Tofu\Model('club', 'music');
var_dump($objClub->count());
exit;
$objClub->remove(array(), array("justOne" => false));
echo $objClub->count();
exit;
