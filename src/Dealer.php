<?php
require_once(__DIR__ . '/../config/constants.php');
require_once(__DIR__ . '/Hand.php');

/**
 * ディーラークラス
 */
class Dealer
{
    /** トランプ 山札デッキ */
    private Hand $deck;

    /** 手札 */
    private array $myHand = [];

    /** プレイヤー */
    private Player $player;

    /** 点数 */
    private int $score = 0;

    public function __construct(Player $player)
    {
        $this->deck = new Hand();
        $this->player = $player;
        $this->gameStart();
    }

    /**
     * ゲームの開始を宣言します。
     *
     * @return void
     */
    private function gameStart(): void
    {
        echo "ブラックジャックを開始します。" . PHP_EOL;
        $this->drawCard($this->player, 2);
        $this->drawCard($this, 2);
        $this->playerTurn();
        $this->dealerTurn();
        $this->gameResult();
    }

    /**
     * デッキからカードを引いてPlayerにカードを配る。
     * Playerに手札と点数を覚えさせる。
     *
     * @param object $obj カードを配りたいオブジェクト
     * @param int $count ループ処理の実行回数
     * @return void
     */
    private function drawCard(object $obj, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            // デッキからカードを1枚抜いたので、デッキを1枚減らす
            $decks = $this->deck->getCard();
            $drawnCard = array_shift($decks);
            $this->deck->setCard($decks);
            // カードを配り現在の点数を集計
            $obj->setCard($drawnCard);
            $obj->setScore($drawnCard->getScore());
            if ($obj instanceof Player) {
                echo "あなたの引いたカードは{$drawnCard->getSuit()}の{$drawnCard->getNumber()}です." . PHP_EOL;
            } else {
                if (count($obj->getCard()) !== DEALER_SECOND_CARD) {
                    echo "ディーラーの引いたカードは{$drawnCard->getSuit()}の{$drawnCard->getNumber()}です." . PHP_EOL;
                } else {
                    echo "ディーラーの引いた2枚目のカードはわかりません。" . PHP_EOL;
                }
            }
        }
    }

    /**
     * プレイヤーのターンを処理します。
     *
     * @return void
     */
    private function playerTurn(): void
    {
        while ($this->player->getScore() <= WINNING_SCORE) {
            echo "あなたの現在の得点は{$this->player->getScore()}です。カードを引きますか？（Y / N）";
            $userInput = trim(fgets(STDIN));
            echo  $userInput . PHP_EOL;
            if ($userInput === 'Y' || $userInput === 'y') {
                $this->drawCard($this->player, 1);
            } elseif ($userInput === 'N' || $userInput === 'n') {
                break;
            } else {
                echo "無効な値です。（Y / N）のどちらかを入力してください。" . PHP_EOL;
            }
        }

        // プレイヤーのカードの合計値が21(WINNING_SCORE)を超えたらプレイヤーの負け
        if (!($this->player->getScore() <= WINNING_SCORE)) {
            echo "あなたの得点は{$this->player->getScore()}です。" . PHP_EOL;
            echo "ディーラーの得点は{$this->getScore()}です。" . PHP_EOL;
            echo "あなたの負けです！" . PHP_EOL;
            echo "ブラックジャックを終了します。" . PHP_EOL;
            return;
        }
    }

    /**
     * ディーラーのターンを処理します。
     */
    private function dealerTurn(): void
    {
        echo "ディーラーの引いた2枚目のカードは{$this->myHand[1]->getSuit()}の{$this->myHand[1]->getNumber()}でした。" . PHP_EOL;
        while ($this->getScore() <= DEALER_MAX_SCORE) {
            echo "ディーラーの現在の得点は{$this->getScore()}です。" . PHP_EOL;
            $this->drawCard($this, 1);
        }
    }

    /**
     * ゲーム結果を表示します。
     */
    private function gameResult(): void
    {
        echo "あなたの得点は{$this->player->getScore()}です。" . PHP_EOL;
        echo "ディーラーの得点は{$this->getScore()}です。" . PHP_EOL;
        if (abs(WINNING_SCORE - $this->player->getScore()) < abs(WINNING_SCORE - $this->getScore())) {
            echo "あなたの勝ちです！" . PHP_EOL;
        } else {
            echo "ディーラーの勝ちです！" . PHP_EOL;
        }
        echo "ブラックジャックを終了します。" . PHP_EOL;
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
}
