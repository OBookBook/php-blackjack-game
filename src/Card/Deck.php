<?php

namespace BlackJack\Card;

class Deck
{
    private array  $trump = [];

    /**
     * 52枚のトランプを生成する。
     *
     * @return array deck
     */
    public function create(): array
    {
        for ($i = 1; $i <= 13; $i++) {
            foreach ([SUIT_SPADE, SUIT_DIAMOND, SUIT_CLUB, SUIT_HEART] as $suit) {
                $this->trump[] = new Trump($suit, $this->convertNumberToCardValue($i), $this->convertToCardScore($i));
            }
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
        if ($number == 1)  return 'A';
        if ($number == 11) return 'J';
        if ($number == 12) return 'Q';
        if ($number == 13) return 'K';
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
        if ($input >= 10) return 10;
        return $input;
    }
}
