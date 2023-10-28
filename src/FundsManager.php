<?php
require_once(__DIR__ . '/../config/constants.php');

/**
 * ゲームのスコアを管理するクラスです。
 */
class FundsManager
{
    private $filePath;

    /**
     * FundsManager constructor.
     */
    public function __construct()
    {
        $this->filePath = FUNDS_FILE_PATH;
    }

    /**
     * スコアを取得します。
     * @return int スコア
     */
    public function getMoney(): int
    {
        if (file_exists($this->filePath)) {
            $scoreData = json_decode(file_get_contents($this->filePath), true);
            return $scoreData["score"] ?? 0;
        }
        return 0;
    }

    /**
     * スコアを設定します。
     * @param int $score 設定する資金
     */
    public function setMoney(int $score): void
    {
        $scoreData = ["score" => $score];
        file_put_contents($this->filePath, json_encode($scoreData));
    }
}
