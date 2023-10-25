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
     * @return void
     */
    private function createTrump(): void
    {
        for ($number = 1; $number <= 13; $number++) {
            $this->addCard(new Card("スペード", $number));
            $this->addCard(new Card("ダイヤ", $number));
            $this->addCard(new Card("クラブ", $number));
            $this->addCard(new Card("ハート", $number));
        }
        var_dump($this->trump);
    }

    /**
     * カードを加える。
     *
     * @param Card $trump 加えるカード
     */
    private function addCard(Card $trump)
    {
        $this->trump[] = $trump;
    }
}
