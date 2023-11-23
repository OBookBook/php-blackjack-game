<?php

namespace BlackJackGame;

class CreateTrump
{
    private array  $trump = [];

    /**
     * 52枚のトランプを生成する。
     *
     * @return array trump
     */
    public function create(): array
    {
        for ($i = 1; $i <= 13; $i++) {
            $this->addTrump(new Trump(SUIT_SPADE, $this->convertNumberToCardValue($i), $this->convertToCardScore($i)));
            $this->addTrump(new Trump(SUIT_DIAMOND, $this->convertNumberToCardValue($i), $this->convertToCardScore($i)));
            $this->addTrump(new Trump(SUIT_CLUB, $this->convertNumberToCardValue($i), $this->convertToCardScore($i)));
            $this->addTrump(new Trump(SUIT_HEART, $this->convertNumberToCardValue($i), $this->convertToCardScore($i)));
        }
        shuffle($this->trump);
        return $this->trump;
    }

    /**
     * カードを加える。
     *
     * @param Trump $trump 生成したトランプ
     * @return void
     */
    private function addTrump(Trump $trump): void
    {
        $this->trump[] = $trump;
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
}
