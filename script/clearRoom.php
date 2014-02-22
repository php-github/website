<?php
require(__DIR__ . '/../webroot/init.php');
$objClub = new Tofu\Model('club', 'room');
echo $objClub->count();
exit;
$objClub->remove(array(), array("justOne" => false));
echo $objClub->count();
exit;
