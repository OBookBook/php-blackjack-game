<?php
require_once(__DIR__ . '/src/Dealer.php');
require_once(__DIR__ . '/src/Player.php');

$palyer = new Player("主人公");
$dealer = new Dealer();
$dealer->setPlayer($palyer);
$dealer->gameStart();
