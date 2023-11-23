<?php

namespace BlackJackGame;

/**
 * コンピュータのプレイヤー
 */
class ComputerPlayer extends Player
{
    public function __construct(string $name)
    {
        parent::__construct($name);
    }
    /**
     * カードを引く or 引かない を自動選択します。
     *
     * @param GameMmaster $gameMmaster
     *
     * @return void
     */
    protected function drawCardOrQuit(GameMmaster $gameMmaster): void
    {
        echo "{$this->getName()}の現在の得点は{$this->getScore()}です。カードを引きますか？（Y / N）" . PHP_EOL;

        switch (rand(1, 2)) {
            case 1:
                echo  "Y" . PHP_EOL;
                $gameMmaster->drawCard(array($this), 1);
                break;
            case 2:
                echo  "N" . PHP_EOL;
                break;
        }
    }
}
