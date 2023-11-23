<?php

namespace BlackJack;

class SplitChecker
{
    public function __construct($players, $gameManager)
    {
        $this->check($players, $gameManager);
    }

    private function check($players, $gameManager)
    {
        foreach ($players as $humanPlayer) {
            if (!($humanPlayer instanceof HumanPlayer)) {
                continue;
            }
            if (!($humanPlayer->getCard()[0]->getNumber() === $humanPlayer->getCard()[1]->getNumber())) {
                continue;
            }
            $userInput = $this->askForSplitChoice();
            if ($userInput === 'Y' || $userInput === 'y') {
                $humanPlayerHund = $humanPlayer->getCard();
                $drawnHund = array_shift($humanPlayerHund);
                $humanPlayer->setSwapCard($humanPlayerHund);
                $newPlayer = new HumanPlayer("P)あなたの分身 ");
                $newPlayer->setCard($drawnHund);
                $gameManager->setPlayer($newPlayer);
            }
        }
    }

    private function askForSplitChoice(): string
    {
        echo "同じ数字が揃いました。スプリットしますか？（Y / N）";
        $userInput = trim(fgets(STDIN));
        echo $userInput . PHP_EOL;
        return $userInput;
    }
}
