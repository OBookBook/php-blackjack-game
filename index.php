<?php
require_once(__DIR__ . '/src/GameMmaster.php');
require_once(__DIR__ . '/src/HumanPlayer.php');
require_once(__DIR__ . '/src/ComputerPlayer.php');

$GameMmaster = new GameMmaster();

$palyer = new HumanPlayer("あなた ");
$GameMmaster->setPlayer($palyer);

$cpu1 = new ComputerPlayer("コンピューター A ");
$GameMmaster->setPlayer($cpu1);

$cpu2 = new ComputerPlayer("コンピューター B ");
$GameMmaster->setPlayer($cpu2);

$GameMmaster->gameStart();
