<?php

/**
 * トランプを生成する為のカードクラス
 */
class Card
{
    /** カードのスート */
    private string $suit;

    /** カードの数字 */
    private int $number;

    /**
     * コンストラクタ。
     *
     * @param string $suit カードのスート
     * @param int $number カードの数字
     */
    public function __construct(string $suit, int $number)
    {
        $this->suit = $suit;
        $this->number = $number;
    }

    /**
     * 数字を見る。
     *
     * @return int 数
     */
    public function getNumber(): int
    {
        return $this->number;
    }
}
