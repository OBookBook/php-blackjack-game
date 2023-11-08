<?php
require_once(__DIR__ . '/CreateTrump.php');
require_once(__DIR__ . '/PlayingGameDeck.php');

class GameMmaster
{
    /** デッキ(山札) */
    private PlayingGameDeck $playingGameDeck;

    /** プレイヤー */
    private array $players = [];

    /** ディーラー */
    private Dealer $dealer;

    public function __construct()
    {
        // トランプを生成してデッキを作成
        $created_deck = (new CreateTrump())->create();
        // 作成したデッキをゲームで使う
        $this->playingGameDeck = new PlayingGameDeck($created_deck);
        // ディーラーを作成
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

        // HACK: 冗長なコード。後でリファクタリングする。
        // NOTE:提出 QUESTステップ4 スプリットを追加 同じ数字が2枚揃った時100BET払い、分裂して2プレイ操作を可能とする。
        foreach ($this->getPlayer() as $humanPlayer) {
            if ($humanPlayer instanceof HumanPlayer) {
                if ($humanPlayer->getCard()[0]->getNumber() === $humanPlayer->getCard()[1]->getNumber()) {
                    echo "同じ数字が揃いました。スプリットしますか？（Y / N）";
                    $userInput = trim(fgets(STDIN));
                    echo  $userInput . PHP_EOL;
                    if ($userInput === 'Y' || $userInput === 'y') {
                        $humanPlayerHund = $humanPlayer->getCard();
                        $drawnHund = array_shift($humanPlayerHund);
                        $humanPlayer->setswapCard($humanPlayerHund);
                        $newPlayer = new HumanPlayer("P)あなたの分身 ");
                        $newPlayer->setCard($drawnHund);
                        $this->setPlayer($newPlayer);
                    }
                }
            }
        }

        // ディーラー
        $this->drawCard(array($this->dealer), 2);
        for ($i = 0; $i < count($this->getPlayer()); $i++) {
            $this->players[$i]->playerTurn($this);
        }
        $this->dealerTurn();
        $this->gameResult();
    }

    public function drawCard($players, $count): void
    {
        for ($i = 0; $i < count($players); $i++) {
            for ($j = 0; $j < $count; $j++) {
                // デッキからカードを1枚抜いたので、デッキのカードを1枚減らす
                $decks = $this->playingGameDeck->getCard();
                $drawnCard = array_shift($decks);
                $this->playingGameDeck = $this->playingGameDeck->setCard($decks);
                // デッキから引いたカードを配る
                $players[$i]->setCard($drawnCard);
                // カードのスコアから自分の点数を加算
                $players[$i]->setScore($drawnCard->getScore());
                // NOTE:提出 QUESTステップ2 実装 Aを1点あるいは11点のどちらかで扱うようにプログラムを修正
                if ($drawnCard->getNumber() === "A" && !($players[$i]->getScore() + 11) < 21) {
                    $players[$i]->setScore(1);
                }
                if ($drawnCard->getNumber() === "A" && ($players[$i]->getScore() + 11) < 21) {
                    $players[$i]->setScore(11);
                }
                if ($players[$i] instanceof Player) {
                    echo "{$players[$i]->getName()}の引いたカードは{$drawnCard->getSuit()}の{$drawnCard->getNumber()}です." . PHP_EOL;
                }
                if ($players[$i] instanceof Dealer && count($players[$i]->getCard()) !== DEALER_SECOND_CARD) {
                    echo "ディーラー の引いたカードは{$drawnCard->getSuit()}の{$drawnCard->getNumber()}です." . PHP_EOL;
                }
                if ($players[$i] instanceof Dealer && count($players[$i]->getCard()) === DEALER_SECOND_CARD) {
                    echo "ディーラー の引いた2枚目のカードはわかりません。" . PHP_EOL;
                }
            }
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

        // HACK: 冗長なコードがたくさん。後でリファクタリングする。
        // シングルプレイ用
        if (count($this->players) === SINGLE_GAME_MODE && !($this->dealer->getScore() <= WINNING_SCORE)) {
            echo "ディーラー の得点は{$this->dealer->getScore()}です。" . PHP_EOL;
            echo "{$this->players[0]->getName()}の勝ちです！" . PHP_EOL;
            $fundsManagerInstance = new FundsManager();
            $fundsManagerInstance->setFunds($fundsManagerInstance->getFunds() + $this->players[0]->getBet());
            echo "{$this->players[0]->getName()}は{$this->players[0]->getBet()}ベット勝ちました!! 総資金({$fundsManagerInstance->getFunds()})です。" . PHP_EOL;
            echo "ブラックジャックを終了します。" . PHP_EOL;
            exit;
        }
        // HACK: 冗長なコードがたくさん。後でリファクタリングする。
        // マルチプレイ用
        if (count($this->players) !== SINGLE_GAME_MODE && !($this->dealer->getScore() <= WINNING_SCORE)) {
            echo "ディーラー の得点は{$this->dealer->getScore()}です。" . PHP_EOL;
            echo "ディーラー の負けです。" . PHP_EOL;
            // 勝利したプレイヤーを集計
            $winningPlayers = array_filter($this->players, function ($player) {
                return $player->getRoundResult() == true;
            });
            foreach ($winningPlayers as $player) {
                echo "{$player->getName()}の勝ちです！" . PHP_EOL;
                $fundsManagerInstance = new FundsManager();
                $fundsManagerInstance->setFunds($fundsManagerInstance->getFunds() + $player->getBet());
                echo "{$player->getName()}は{$player->getBet()}ベット勝ちました!! 総資金({$fundsManagerInstance->getFunds()})です。" . PHP_EOL;
            }
            echo "ブラックジャックを終了します。" . PHP_EOL;
            exit;
        }
    }

    /**
     * ゲーム結果を表示します。
     *
     * @return void
     */
    private function gameResult(): void
    {
        // 勝利したプレイヤーを集計
        $winningPlayers = array_filter($this->players, function ($player) {
            return $player->getRoundResult() == true;
        });
        foreach ($winningPlayers as $player) {
            echo "{$player->getName()}の得点は{$player->getScore()}です。" . PHP_EOL;
            echo "ディーラーの得点は{$this->dealer->getScore()}です。" . PHP_EOL;
            if (abs(WINNING_SCORE - $player->getScore()) < abs(WINNING_SCORE - $this->dealer->getScore())) {
                echo "{$player->getName()}の勝ちです！" . PHP_EOL;
                $fundsManagerInstance = new FundsManager();
                $fundsManagerInstance->setFunds($fundsManagerInstance->getFunds() + $player->getBet());
                echo "{$player->getName()}は{$player->getBet()}ベット勝ちました!! 総資金({$fundsManagerInstance->getFunds()})です。" . PHP_EOL;
            } else {
                echo "ディーラーの勝ちです！" . PHP_EOL;
                $fundsManagerInstance = new FundsManager();
                $fundsManagerInstance->setFunds($fundsManagerInstance->getFunds() - $player->getBet());
                echo "{$player->getName()}は{$player->getBet()}ベット失いました。 総資金({$fundsManagerInstance->getFunds()})です。" . PHP_EOL;
            }
        }
        echo "ブラックジャックを終了します。" . PHP_EOL;
        exit;
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
