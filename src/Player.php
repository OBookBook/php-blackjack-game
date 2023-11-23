<?php

namespace BlackJackGame;

/**
 * プレイヤークラス
 */
abstract class Player
{
    /** 手札 */
    protected  array $myHand = [];

    /** 点数 */
    protected  int $score = 0;

    /** 名前 */
    protected  string $name;

    /** ゲームの勝敗 */
    protected  bool $roundResult = true;

    /** サレンダー フラグ */
    protected  bool $isSurrendered = false;

    /** ベット */
    protected  int $bet = BET_100;

    /** 資金 */
    protected  int $funds;

    /**
     * コンストラクタ。
     *
     * @param string $name 名前
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        if ($this instanceof HumanPlayer) {
            $fundsManagerInstance = new FundsManager();
            $this->funds = $fundsManagerInstance->getFunds();
            echo "{$this->getName()}は100ベット支払いゲームに参加しました。残資金({$fundsManagerInstance->getFunds()})" . PHP_EOL;
        }
    }

    /**
     * プレイヤーのターンを処理します。
     *
     * @param GameMmaster $dealer
     * @return void
     */
    public function playerTurn(GameMmaster $dealer): void
    {
        $this->drawCardOrQuit($dealer);

        if (!($this->getScore() <= WINNING_SCORE)) {
            $this->setRoundResult(false);
        }
    }

    /**
     * カードを引く or 引かない を選択します。
     *
     * @param GameMmaster $gameMmaster
     *
     * @return void
     */
    abstract protected function drawCardOrQuit(GameMmaster $gameMmaster): void;

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
    public function setCard(Trump $tranp): void
    {
        $this->myHand[] = $tranp;
    }

    /**
     * 手札を入れ替える。
     *
     * @param  $tranp カードのスート
     * @return void
     */
    public function setSwapCard($tranp): void
    {
        $this->myHand = $tranp;
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

    /**
     * 自分の名前を返す。
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * ゲームの勝敗を返す。
     *
     * @return int
     */
    public function getRoundResult(): int
    {
        return $this->roundResult;
    }

    /**
     * ゲームの勝敗を設定する。
     *
     * @param bool $result true|false
     * @return void
     */
    public function setRoundResult(bool $result): void
    {
        $this->roundResult = $result;
    }

    public function getIsSurrendered(): int
    {
        return $this->isSurrendered;
    }

    public function setIsSurrendered(bool $result): void
    {
        $this->isSurrendered = $result;
    }

    /**
     * ベットする
     *
     * @param int $bet ベット
     * @return void
     */
    public function setBet(int $bet): void
    {
        $this->bet += $bet;
    }

    /**
     * ベットした金額を返す。
     *
     * @return int
     */
    public function getBet(): int
    {
        return $this->bet;
    }
}
