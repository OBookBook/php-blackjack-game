<?php

namespace BlackJackGame;

require_once "./vendor/autoload.php";

$deckInstance = new Deck();
$deck = $deckInstance->create();

$GameMmaster = new GameMmaster($deck);

$palyer = new HumanPlayer("あなた ");
$GameMmaster->setPlayer($palyer);

$cpu1 = new ComputerPlayer("コンピューター A ");
$GameMmaster->setPlayer($cpu1);

$cpu2 = new ComputerPlayer("コンピューター B ");
$GameMmaster->setPlayer($cpu2);

$GameMmaster->gameStart();
