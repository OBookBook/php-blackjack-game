<?php
require_once(__DIR__ . '/src/Dealer.php');
require_once(__DIR__ . '/src/Player.php');

$palyer = new Player();
new Dealer($palyer);
