<?php
require_once(__DIR__ . '/Dealer.php');

/**
 * プレイヤークラス
 */
class Player
{
    /** 手札 */
    private array $myHand = [];

    /** 点数 */
    private int $score = 0;


    /**
     * プレイヤーのターンを処理します。
     *
     * @return void
     */
    public function playerTurn(Dealer $dealer): void
    {
        while ($this->getScore() <= WINNING_SCORE) {
            echo "あなたの現在の得点は{$this->getScore()}です。カードを引きますか？（Y / N）";
            $userInput = trim(fgets(STDIN));
            echo  $userInput . PHP_EOL;
            if ($userInput === 'Y' || $userInput === 'y') {
                $dealer->drawCard($this, 1);
            } elseif ($userInput === 'N' || $userInput === 'n') {
                break;
            } else {
                echo "無効な値です。（Y / N）のどちらかを入力してください。" . PHP_EOL;
            }
        }

        // プレイヤーのカードの合計値が21(WINNING_SCORE)を超えたらプレイヤーの負け
        if (!($this->getScore() <= WINNING_SCORE)) {
            echo "あなたの得点は{$this->getScore()}です。" . PHP_EOL;
            echo "あなたの負けです！" . PHP_EOL;
            echo "ブラックジャックを終了します。" . PHP_EOL;
            exit;
        }
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
