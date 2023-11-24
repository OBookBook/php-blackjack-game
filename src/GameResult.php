<?php

namespace BlackJack;

class GameResult
{
    public function __construct($players, $dealer, FundsManager $FundsManagerInstance)
    {
        $this->result($players, $dealer, $FundsManagerInstance);
    }

    /**
     * ゲーム結果を表示します。
     *
     * @return void
     */
    private function result($players, $dealer, $fundsManagerInstance): void
    {
        echo "ディーラーの得点は{$dealer->getScore()}です。" . PHP_EOL;
        foreach ($players as $player) {
            echo "{$player->getName()}の得点は{$player->getScore()}です。" . PHP_EOL;
            // プレイヤーがバースト
            if ($player->getRoundResult() == false && $player->getIsSurrendered() == false) {
                $this->handlePlayerBust($player, $fundsManagerInstance);
            }
            // プレイヤーがサレンダー
            if ($player->getRoundResult() == false && $player->getIsSurrendered() == true) {
                $this->handlePlayerSurrendered($player, $fundsManagerInstance);
            }
            // プレイヤーの勝ち
            if ($player->getRoundResult() == true && abs(WINNING_SCORE - $player->getScore()) < abs(WINNING_SCORE - $dealer->getScore())) {
                $this->handlePlayerWin($player, $fundsManagerInstance);
            }
            // ディーラーの勝ち
            if ($player->getRoundResult() == true && !(abs(WINNING_SCORE - $player->getScore()) < abs(WINNING_SCORE - $dealer->getScore()))) {
                $this->handleDealerWin($player, $fundsManagerInstance);
            }
        }
        echo "ブラックジャックを終了します。" . PHP_EOL;
    }

    // プレイヤーがバーストした場合の処理
    private function handlePlayerBust($player, $fundsManagerInstance)
    {
        echo "点数が21以上の為バースト!! {$player->getName()}の負けです！" . PHP_EOL;
        if ($player instanceof HumanPlayer) {
            $fundsManagerInstance->setFunds($fundsManagerInstance->getFunds() - $player->getBet());
            echo "{$player->getName()}は{$player->getBet()}ベット失いました。 総資金({$fundsManagerInstance->getFunds()})です。" . PHP_EOL;
        }
    }

    // プレイヤーがサレンダーした場合の処理
    private function handlePlayerSurrendered($player, $fundsManagerInstance)
    {
        echo "{$player->getName()}はサレンダーを宣言していたので、半額の50円を返却します。残資金({$fundsManagerInstance->getFunds()})" . PHP_EOL;
        if ($player instanceof HumanPlayer) {
            $fundsManagerInstance->setFunds($fundsManagerInstance->getFunds() - (BET_100 / 2));
            echo "{$player->getName()}は" . (BET_100 / 2) . "ベット失いました。 総資金({$fundsManagerInstance->getFunds()})です。" . PHP_EOL;
        }
    }

    // プレイヤーが勝った場合の処理
    private function handlePlayerWin($player, $fundsManagerInstance)
    {
        echo "{$player->getName()}の勝ちです！" . PHP_EOL;
        if ($player instanceof HumanPlayer) {
            $fundsManagerInstance->setFunds($fundsManagerInstance->getFunds() + $player->getBet());
            echo "{$player->getName()}は{$player->getBet()}ベット勝ちました!! 総資金({$fundsManagerInstance->getFunds()})です. " . PHP_EOL;
        }
    }

    // ディーラーが勝った場合の処理
    private function handleDealerWin($player, $fundsManagerInstance)
    {
        echo "ディーラーの勝ちです！" . PHP_EOL;
        if ($player instanceof HumanPlayer) {
            $fundsManagerInstance->setFunds($fundsManagerInstance->getFunds() - $player->getBet());
            echo "{$player->getName()}は{$player->getBet()}ベット失いました。 総資金({$fundsManagerInstance->getFunds()})です. " . PHP_EOL;
        }
    }
}
