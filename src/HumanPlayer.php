<?php
require_once(__DIR__ . '/Player.php');

/**
 * ゲーム操作可能なプレイヤー
 */
class HumanPlayer extends Player
{
    public function __construct(string $name)
    {
        parent::__construct($name);
    }
    /**
     * カードを引く or 引かない をプレイヤーが選択します。
     *
     * @param Dealer $dealer ディーラー。
     *
     * @return void
     */
    protected function drawCardOrQuit(Dealer $dealer): void
    {
        while ($this->getScore() <= WINNING_SCORE) {
            echo "{$this->getName()}の現在の得点は{$this->getScore()}です。カードを引きますか？（Y / N）";
            $userInput = trim(fgets(STDIN));
            echo  $userInput . PHP_EOL;
            if ($userInput === 'Y' || $userInput === 'y') {
                $dealer->drawCard(array($this), 1);
            } elseif ($userInput === 'N' || $userInput === 'n') {
                break;
            } else {
                echo "無効な値です。（Y / N）のどちらかを入力してください。" . PHP_EOL;
            }
        }
    }
}
