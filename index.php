<?php

require_once "./vendor/autoload.php";

$deckInstance = new BlackJack\Card\Deck();
$deck = $deckInstance->create();
$fundsManagerInstance = new BlackJack\FundsManager();
$GameMmaster = new BlackJack\GameMmaster($deck, $fundsManagerInstance);

$palyer = new BlackJack\HumanPlayer("あなた ");
$GameMmaster->setPlayer($palyer);

$cpu1 = new BlackJack\ComputerPlayer("コンピューター A ");
$GameMmaster->setPlayer($cpu1);

$cpu2 = new BlackJack\ComputerPlayer("コンピューター B ");
$GameMmaster->setPlayer($cpu2);

$GameMmaster->gameStart();
