<?php
require_once(__DIR__ . '/../config/constants.php');
require_once(__DIR__ . '/Hand.php');
require_once(__DIR__ . '/Player.php');

/**
 * ディーラークラス
 */
class Dealer
{
    /** トランプ 山札デッキ */
    private Hand $deck;

    /** ディーラーの手札 */
    private array $myHand = [];

    /** 点数 */
    private int $score = 0;

    /** プレイヤー */
    private array $player = [];

    public function __construct()
    {
        $this->deck = new Hand();
    }

    /**
     * ゲームの開始を宣言します。
     *
     * @return void
     */
    public function gameStart(): void
    {
        echo "ブラックジャックを開始します。" . PHP_EOL;
        $this->drawCard($this->getPlayer(), 2);

        // HACK: 冗長なコード。後でリファクタリングする。
        // NOTE:提出 QUESTステップ4 スプリットを追加 同じ数字が2枚揃った時100BET払い、分裂して2プレイ操作を可能とする。
        foreach ($this->getPlayer() as $humanPlayer) {
            if ($humanPlayer instanceof HumanPlayer) {
                if ($humanPlayer->getCard()[0]->getNumber() === $humanPlayer->getCard()[1]->getNumber()) {
                    echo "同じ数字が揃いました。スプリットしますか？（Y / N）";
                    $userInput = trim(fgets(STDIN));
                    echo  $userInput . PHP_EOL;
                    if ($userInput === 'Y' || $userInput === 'y') {
                        $humanPlayerHund = $humanPlayer->getCard();
                        $drawnHund = array_shift($humanPlayerHund);
                        $humanPlayer->setswapCard($humanPlayerHund);
                        $newPlayer = new HumanPlayer("P)あなたの分身 ");
                        $newPlayer->setCard($drawnHund);
                        $this->setPlayer($newPlayer);
                    }
                }
            }
        }
        $this->drawCard(array($this), 2);
        for ($i = 0; $i < count($this->getPlayer()); $i++) {
            $this->player[$i]->playerTurn($this);
        }
        $this->dealerTurn();
        $this->gameResult();
    }

    /**
     * デッキからカードを引いてPlayerにカードを配る。
     * Playerに手札と点数を覚えさせる。
     *
     * @param array $obj カードを配りたいオブジェクト
     * @param int $count ループ処理の実行回数
     * @return void
     */
    public function drawCard(array $obj, int $count): void
    {
        for ($i = 0; $i < count($obj); $i++) {
            for ($j = 0; $j < $count; $j++) {
                // デッキからカードを1枚抜いたので、デッキのカードを1枚減らす
                $decks = $this->deck->getCard();
                $drawnCard = array_shift($decks);
                $this->deck->setCard($decks);
                // カードを手札に加え、現在の点数を集計
                $obj[$i]->setCard($drawnCard);
                // NOTE:提出 QUESTステップ2 実装 Aを1点あるいは11点のどちらかで扱うようにプログラムを修正
                if (!($drawnCard->getNumber() === "A")) {
                    $obj[$i]->setScore($drawnCard->getScore());
                }
                if ($drawnCard->getNumber() === "A") {
                    if (($obj[$i]->getScore() + 11) < 21) {
                        $obj[$i]->setScore(11);
                    } else {
                        $obj[$i]->setScore(1);
                    }
                }
                if ($obj[$i] instanceof Player) {
                    echo "{$obj[$i]->getName()}の引いたカードは{$drawnCard->getSuit()}の{$drawnCard->getNumber()}です." . PHP_EOL;
                } else {
                    if (count($obj[$i]->getCard()) !== DEALER_SECOND_CARD) {
                        echo "ディーラー の引いたカードは{$drawnCard->getSuit()}の{$drawnCard->getNumber()}です." . PHP_EOL;
                    } else {
                        echo "ディーラー の引いた2枚目のカードはわかりません。" . PHP_EOL;
                    }
                }
            }
        }
    }

    /**
     * ディーラーのターンを処理します。
     *
     * @return void
     */
    private function dealerTurn(): void
    {
        echo "ディーラー の引いた2枚目のカードは{$this->myHand[1]->getSuit()}の{$this->myHand[1]->getNumber()}でした。" . PHP_EOL;
        while ($this->getScore() <= DEALER_MAX_SCORE) {
            echo "ディーラー の現在の得点は{$this->getScore()}です。" . PHP_EOL;
            $this->drawCard(array($this), 1);
        }

        // HACK: 冗長なコードがたくさん。後でリファクタリングする。
        // シングルプレイ用
        if (count($this->getPlayer()) === SINGLE_GAME_MODE && !($this->getScore() <= WINNING_SCORE)) {
            echo "ディーラー の得点は{$this->getScore()}です。" . PHP_EOL;
            echo "{$this->player[0]->getName()}の勝ちです！" . PHP_EOL;
            $fundsManagerInstance = new FundsManager();
            $fundsManagerInstance->setFunds($fundsManagerInstance->getFunds() + $this->player[0]->getBet());
            echo "{$this->player[0]->getName()}は{$this->player[0]->getBet()}ベット勝ちました!! 総資金({$fundsManagerInstance->getFunds()})です。" . PHP_EOL;
            echo "ブラックジャックを終了します。" . PHP_EOL;
            exit;
        }
        // HACK: 冗長なコードがたくさん。後でリファクタリングする。
        // マルチプレイ用
        if (count($this->getPlayer()) !== SINGLE_GAME_MODE && !($this->getScore() <= WINNING_SCORE)) {
            echo "ディーラー の得点は{$this->getScore()}です。" . PHP_EOL;
            echo "ディーラー の負けです。" . PHP_EOL;
            // 勝利したプレイヤーを集計
            $winningPlayers = array_filter($this->getPlayer(), function ($player) {
                return $player->getRoundResult() == true;
            });
            foreach ($winningPlayers as $player) {
                echo "{$player->getName()}の勝ちです！" . PHP_EOL;
                $fundsManagerInstance = new FundsManager();
                $fundsManagerInstance->setFunds($fundsManagerInstance->getFunds() + $player->getBet());
                echo "{$player->getName()}は{$player->getBet()}ベット勝ちました!! 総資金({$fundsManagerInstance->getFunds()})です。" . PHP_EOL;
            }
            echo "ブラックジャックを終了します。" . PHP_EOL;
            exit;
        }
    }

    /**
     * ゲーム結果を表示します。
     *
     * @return void
     */
    private function gameResult(): void
    {
        // 勝利したプレイヤーを集計
        $winningPlayers = array_filter($this->getPlayer(), function ($player) {
            return $player->getRoundResult() == true;
        });
        foreach ($winningPlayers as $player) {
            echo "{$player->getName()}の得点は{$player->getScore()}です。" . PHP_EOL;
            echo "ディーラーの得点は{$this->getScore()}です。" . PHP_EOL;
            if (abs(WINNING_SCORE - $player->getScore()) < abs(WINNING_SCORE - $this->getScore())) {
                echo "{$player->getName()}の勝ちです！" . PHP_EOL;
                $fundsManagerInstance = new FundsManager();
                $fundsManagerInstance->setFunds($fundsManagerInstance->getFunds() + $player->getBet());
                echo "{$player->getName()}は{$player->getBet()}ベット勝ちました!! 総資金({$fundsManagerInstance->getFunds()})です。" . PHP_EOL;
            } else {
                echo "ディーラーの勝ちです！" . PHP_EOL;
                $fundsManagerInstance = new FundsManager();
                $fundsManagerInstance->setFunds($fundsManagerInstance->getFunds() - $player->getBet());
                echo "{$player->getName()}は{$player->getBet()}ベット失いました。 総資金({$fundsManagerInstance->getFunds()})です。" . PHP_EOL;
            }
        }
        echo "ブラックジャックを終了します。" . PHP_EOL;
        exit;
    }

    /**
     * 手札を見る。
     *
     * @return array
     */
    public function getCard(): array
    {
        return $this->myHand;
    }

    /**
     * 手札を追加する。
     *
     * @param Card $tranp カードのスート
     * @return void
     */
    public function setCard(Card $tranp): void
    {
        $this->myHand[] = $tranp;
    }

    /**
     * 点数を返す。
     *
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * 点数を追加する。
     *
     * @param int $score 点数
     * @return void
     */
    public function setScore(int $score): void
    {
        $this->score += $score;
    }

    /**
     * ゲームに参加しているプレイヤーを取得
     *
     * @return array
     */
    public function getPlayer(): array
    {
        return $this->player;
    }

    /**
     * 手札を追加する。
     *
     * @param Player $player カードのスート
     * @return void
     */
    public function setPlayer(Player $player): void
    {
        $this->player[] = $player;
    }
}
