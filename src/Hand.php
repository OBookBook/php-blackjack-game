<?php
require_once(__DIR__ . '/../config/constants.php');
require_once(__DIR__ . '/Card.php');
/**
 * 手札クラス
 */
class Hand
{
    /** 生成したトランプ */
    private array  $trump = [];

    public function __construct()
    {
        $this->createTrump();
    }

    /**
     * 52枚のトランプを生成する。
     *
     * @return array trump
     */
    private function createTrump(): array
    {
        for ($i = 1; $i <= 13; $i++) {
            $this->addCard(new Card(SUIT_SPADE, $this->convertNumberToCardValue($i), $this->convertToCardScore($i)));
            $this->addCard(new Card(SUIT_DIAMOND, $this->convertNumberToCardValue($i), $this->convertToCardScore($i)));
            $this->addCard(new Card(SUIT_CLUB, $this->convertNumberToCardValue($i), $this->convertToCardScore($i)));
            $this->addCard(new Card(SUIT_HEART, $this->convertNumberToCardValue($i), $this->convertToCardScore($i)));
        }
        shuffle($this->trump);
        return $this->trump;
    }

    /**
     * トランプの数字をカードの値に変換します。
     *
     * @param int $number トランプの数字
     * @return string カードの値（A、2、3、...、J、Q、K）
     */
    private function convertNumberToCardValue(int $number): string
    {
        if ($number == 1) {
            return 'A';
        } elseif ($number == 11) {
            return 'J';
        } elseif ($number == 12) {
            return 'Q';
        } elseif ($number == 13) {
            return 'K';
        }
        return (string)$number;
    }

    /**
     * トランプの数字をカードの得点に変換します。
     *
     * @param int $input トランプの数字
     * @return int カードの得点（2 から 10 はそのまま、11 から 13 は 10）
     */
    private function convertToCardScore(int $input): int
    {
        if ($input >= 10) {
            return 10;
        } else {
            return $input;
        }
    }

    /**
     * カードを加える。
     *
     * @param Card $trump 生成したトランプ
     * @return void
     */
    private function addCard(Card $trump): void
    {
        $this->trump[] = $trump;
    }

    /**
     * 生成したトランプを返す。
     *
     * @return array $trump 生成したトランプ
     */
    public function getCard(): array
    {
        return $this->trump;
    }
    /**
     * カードを詰め直す。
     *
     * @return void
     */
    public function setCard($cards): void
    {
        $this->trump = $cards;
    }
}
