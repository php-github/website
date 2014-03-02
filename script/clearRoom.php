<?php
require(__DIR__ . '/../webroot/init.php');
$objClub = new Tofu\Model('club', 'album');
echo $objClub->count();
exit;
$objClub->remove(array(), array("justOne" => false));
echo $objClub->count();
exit;
