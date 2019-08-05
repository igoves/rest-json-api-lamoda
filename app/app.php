<?php

use Core\Core;

require_once '../vendor/autoload.php';

$core = new Core;

if (isset($_POST['generator'])) {
    $info = $core->generator();
}

$main_data = $core->init();
$total_containers = $main_data['total_containers'];
$unique_products = $main_data['unique_products'];
$capacity_products = $main_data['capacity_products'];
$random_id = $main_data['random_id'];
$info = !isset($info) || !empty($info) ? $main_data['info'] : $info;


