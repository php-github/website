<?php
require(__DIR__ . '/../webroot/init.php');
$objClub = new Tofu\Model('club', 'room');
$objClub->remove(array(), array("justOne" => false));
echo $objClub->count();
exit;
