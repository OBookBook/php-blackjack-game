<?php

namespace BlackJack;

class GameMmaster
{
    /** デッキ(山札) */
    private array $deck = [];

    /** プレイヤー */
    private array $players = [];

    /** ディーラー */
    private Dealer $dealer;

    private FundsManager $fundsManager;

    public function __construct($deck, $fundsManager)
    {
        $this->deck = $deck;
        $this->fundsManager = $fundsManager;
        $this->dealer = new Dealer();
    }

    /**
     * ゲームの開始を宣言します。
     *
     * @return void
     */
    public function gameStart(): void
    {
        echo "ブラックジャックを開始します。" . PHP_EOL;
        $this->drawCard($this->players, 2);
        SplitChecker::check($this->players, $this);
        $this->drawCard(array($this->dealer), 2);
        $this->playerTurns();
        $this->dealerTurn();
        GameResult::result($this->players, $this->dealer, $this->fundsManager);
    }

    public function drawCard($players, $count): void
    {
        foreach ($players as $player) {
            for ($j = 0; $j < $count; $j++) {
                $drawnCard = array_shift($this->deck);
                // デッキから引いたカードを配る
                $player->setCard($drawnCard);
                // カードのスコアから自分の点数を加算
                $player->setScore($drawnCard->getScore());
                // NOTE:提出 QUESTステップ2 実装 Aを1点あるいは11点のどちらかで扱うようにプログラムを修正
                if ($drawnCard->getNumber() === "A" && !($player->getScore() + 11) < 21) {
                    $player->setScore(1);
                }
                if ($drawnCard->getNumber() === "A" && ($player->getScore() + 11) < 21) {
                    $player->setScore(11);
                }
                if ($player instanceof Player) {
                    echo "{$player->getName()}の引いたカードは{$drawnCard->getSuit()}の{$drawnCard->getNumber()}です." . PHP_EOL;
                }
                if ($player instanceof Dealer && count($player->getCard()) !== DEALER_SECOND_CARD) {
                    echo "ディーラー の引いたカードは{$drawnCard->getSuit()}の{$drawnCard->getNumber()}です." . PHP_EOL;
                }
                if ($player instanceof Dealer && count($player->getCard()) === DEALER_SECOND_CARD) {
                    echo "ディーラー の引いた2枚目のカードはわかりません。" . PHP_EOL;
                }
            }
        }
    }

    public function playerTurns(): void
    {
        foreach ($this->players as $player) {
            $player->playerTurn($this);
        }
    }

    /**
     * ディーラーのターンを処理します。
     *
     * @return void
     */
    private function dealerTurn(): void
    {
        echo "ディーラー の引いた2枚目のカードは{$this->dealer->getCard()[1]->getSuit()}の{$this->dealer->getCard()[1]->getNumber()}でした。" . PHP_EOL;
        while ($this->dealer->getScore() <= DEALER_MAX_SCORE) {
            echo "ディーラー の現在の得点は{$this->dealer->getScore()}です。" . PHP_EOL;
            $this->drawCard(array($this->dealer), 1);
        }
    }

    /**
     * ゲームに参加しているプレイヤーを取得
     *
     * @return array
     */
    public function getPlayer(): array
    {
        return $this->players;
    }

    /**
     * プレイヤーを追加する。
     *
     * @param Player $player カードのスート
     * @return void
     */
    public function setPlayer(Player $player): void
    {
        $this->players[] = $player;
    }
}
