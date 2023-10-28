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
            echo "{$this->getName()}の現在の得点は{$this->getScore()}です。カードを引きますか？（Y / N） サレンダー？（S）";
            $userInput = trim(fgets(STDIN));
            echo  $userInput . PHP_EOL;
            if ($userInput === 'Y' || $userInput === 'y') {
                $dealer->drawCard(array($this), 1);
            } elseif ($userInput === 'N' || $userInput === 'n') {
                break;
            } elseif ($userInput === 'S' || $userInput === 's') {
                // NOTE:提出 QUESTステップ4 サレンダーを追加 1ゲーム100ベット固定にしたので、半額の50円返してもらいゲームを終了する事ができます。
                $fundsManagerInstance = new FundsManager();
                $fundsManagerInstance->setFunds($fundsManagerInstance->getFunds() - 50);
                echo "{$this->getName()}はサレンダーを宣言しました。半額の50円を返却します。残資金({$fundsManagerInstance->getFunds()})" . PHP_EOL;
                $this->setRoundResult(false);
                return;
            } else {
                echo "無効な値です。（Y / N）のどちらかを入力してください。" . PHP_EOL;
            }
        }
    }
}
