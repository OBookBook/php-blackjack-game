<?php

/**
 * トランプを生成する為のカードの雛形
 */
class Trump
{
    /** カードのスート */
    private string $suit;

    /** カードの数字 */
    private string $number;

    /** カードの点数 */
    private int $score;

    /**
     * コンストラクタ。
     *
     * @param string $suit カードのスート
     * @param string $number カードの数字
     * @param int $score カードの点数
     */
    public function __construct(string $suit, string $number, int $score)
    {
        $this->suit = $suit;
        $this->number = $number;
        $this->score = $score;
    }

    /**
     * スートを見る。
     *
     * @return string カードのスート
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * 数字を見る。
     *
     * @return string 数
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * 点数をを見る。
     *
     * @return int 点数
     */
    public function getScore(): int
    {
        return $this->score;
    }
}
