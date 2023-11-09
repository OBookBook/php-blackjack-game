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
     * @param GameMmaster $gameMmaster
     *
     * @return void
     */
    protected function drawCardOrQuit(GameMmaster $gameMmaster): void
    {
        // カードを引きますか？（Y / N）ダブルダウン？（D） サレンダー？（S）
        while ($this->getScore() <= WINNING_SCORE) {
            switch (strtolower($this->askUserInput())) {
                case 'y':
                    $gameMmaster->drawCard([$this], 1);
                    break;
                case 'n':
                    return;
                case 'd':
                    // NOTE:提出 QUESTステップ4 ダブルダウンを追加 1枚しかカードを引けない代わりに、最初に賭けたチップと同額を賭ける事が出来る。
                    $this->doubleDown($gameMmaster);
                    return;
                case 's':
                    // NOTE:提出 QUESTステップ4 サレンダーを追加 1ゲーム100ベット固定にしたので、半額の50円返してもらいゲームを終了する事ができます。
                    $this->surrender();
                    return;
                default:
                    echo "無効な値です。（Y / N）のどちらかを入力してください。" . PHP_EOL;
            }
        }
    }

    private function askUserInput(): string
    {
        echo "{$this->getName()}の現在の得点は{$this->getScore()}です。カードを引きますか？（Y / N）ダブルダウン？（D） サレンダー？（S）";
        $userInput = trim(fgets(STDIN));
        echo $userInput . PHP_EOL;
        return $userInput;
    }

    // ダブルダウンの処理
    private function doubleDown(GameMmaster $dealer): void
    {
        $this->setBet(100);
        $fundsManagerInstance = new FundsManager();
        echo "{$this->getName()}はダブルダウンを宣言。追加で100ベットして現在({$this->getBet()}ベット)。残資金({$fundsManagerInstance->getFunds()})" . PHP_EOL;
        $dealer->drawCard([$this], 1);
    }

    // サレンダーの処理
    private function surrender(): void
    {
        echo "{$this->getName()}はサレンダーを宣言、このゲームを降りました" . PHP_EOL;
        $this->setRoundResult(false);
        $this->setIsSurrendered(true);
    }
}
