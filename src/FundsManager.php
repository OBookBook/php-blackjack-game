<?php

namespace BlackJack;

/**
 * プレイヤーの資金を管理するクラスです。
 */
class FundsManager
{
    /**
     * funds.jsonファイルのファイルパスを格納
     */
    private string $filePath;

    /**
     * FundsManager constructor.
     */
    public function __construct()
    {
        $this->filePath = FUNDS_FILE_PATH;
    }

    /**
     * プレイヤーの資金を取得します。
     * @return int プレイヤーの資金
     */
    public function getFunds(): int
    {
        if (file_exists($this->filePath)) {
            $fundsData = json_decode(file_get_contents($this->filePath), true);
            return $fundsData["funds"] ?? 0;
        }
        return 0;
    }

    /**
     * ゲーム修了後 プレイヤーの資金を登録します。
     * @param int $funds 設定する資金
     */
    public function setFunds(int $funds): void
    {
        $fundsData = ["funds" => $funds];
        file_put_contents($this->filePath, json_encode($fundsData));
    }
}
