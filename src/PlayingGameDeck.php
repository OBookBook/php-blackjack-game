<?php

namespace BlackJackGame;

/**
 * ゲームで実際に使用しているデッキ
 */
class PlayingGameDeck
{

    private array $deck;

    public function __construct($playing_deck)
    {
        // if (!is_array($playing_deck)) {
        //     throw new InvalidArgumentException("配列を指定してください。");
        // }

        $this->deck = $playing_deck;
    }

    public function getCard(): array
    {
        return $this->deck;
    }

    public function setCard($playing_deck)
    {
        // if (!is_array($playing_deck)) {
        //     throw new InvalidArgumentException("配列を指定してください。");
        // }

        return new PlayingGameDeck($playing_deck);
    }
}
