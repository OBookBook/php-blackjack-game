<?php
require_once 'Card.php';

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
        for ($number = 1; $number <= 13; $number++) {
            $this->addCard(new Card("スペード", $number));
            $this->addCard(new Card("ダイヤ", $number));
            $this->addCard(new Card("クラブ", $number));
            $this->addCard(new Card("ハート", $number));
        }
        shuffle($this->trump);
        return $this->trump;
    }

    /**
     * カードを加える。
     *
     * @param Card $trump 加えるカード
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
    public function gedCard(): array
    {
        return $this->trump;
    }
}
