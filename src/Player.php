<?php

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
