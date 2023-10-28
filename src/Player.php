<?php
require_once(__DIR__ . '/../config/constants.php');
require_once(__DIR__ . '/FundsManager.php');
require_once(__DIR__ . '/Dealer.php');

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

    /** 資金 */
    protected  int $funds = 1000;

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
        }
    }

    /**
     * プレイヤーのターンを処理します。
     *
     * @param Dealer $dealer
     * @return void
     */
    public function playerTurn(Dealer $dealer): void
    {
        $this->drawCardOrQuit($dealer);

        // シングルプレイ用
        if (count($dealer->getPlayer()) === SINGLE_GAME_MODE && !($this->getScore() <= WINNING_SCORE)) {
            echo "{$this->getName()}の得点は{$this->getScore()}です。" . PHP_EOL;
            echo "点数が21以上になった為、{$this->getName()}の負けです！" . PHP_EOL;
            echo "ブラックジャックを終了します。" . PHP_EOL;
            exit;
        }
        // マルチプレイ用
        if (count($dealer->getPlayer()) !== SINGLE_GAME_MODE && !($this->getScore() <= WINNING_SCORE)) {
            echo "{$this->getName()}の得点は{$this->getScore()}です。" . PHP_EOL;
            echo "点数が21以上になった為、{$this->getName()}は負けです！" . PHP_EOL;
            $this->setRoundResult(false);
        }
    }

    /**
     * カードを引く or 引かない を選択します。
     *
     * @param Dealer $dealer ディーラー。
     *
     * @return void
     */
    abstract protected function drawCardOrQuit(Dealer $dealer): void;

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
}
