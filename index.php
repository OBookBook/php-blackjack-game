<?php
require_once(__DIR__ . '/src/Dealer.php');
require_once(__DIR__ . '/src/HumanPlayer.php');
require_once(__DIR__ . '/src/ComputerPlayer.php');

$dealer = new Dealer();

$palyer = new HumanPlayer("あなた ");
$dealer->setPlayer($palyer);

$cpu1 = new ComputerPlayer("コンピューター A ");
$dealer->setPlayer($cpu1);

$cpu2 = new ComputerPlayer("コンピューター B ");
$dealer->setPlayer($cpu2);

$dealer->gameStart();
