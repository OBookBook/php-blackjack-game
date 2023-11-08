<?php

/**
 * ゲームで実際に使用しているデッキ
 */
class PlayingGameDeck
{

    private array $deck;

    public function __construct($playing_deck)
    {
        // if ($playing_deck < 0) {
        //     throw new InvalidArgumentException("金額には0以上を指定してください。");
        // }
        // if ($playing_deck === null) {
        //     throw new InvalidArgumentException("通貨単位を指定してください。");
        // }
        $this->deck = $playing_deck;
    }

    public function getCard(): array
    {
        return $this->deck;
    }

    // カードを引く処理もここに書けばいいと思うよ
    public function setCard($playing_deck)
    {
        // if ($this->GameDeck !== $playing_deck) {
        //     throw new InvalidArgumentException("通貨単位が違います。");
        // }

        return new PlayingGameDeck($playing_deck);
    }
}
