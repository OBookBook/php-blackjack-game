<?php
require_once 'Hand.php';

/**
 * ディーラークラス
 */
class Dealer
{
    /** トランプ */
    private Hand $tranp;

    /** 手札 */
    private $myHand = [];

    public function __construct()
    {
        $this->tranp = new Hand();
        print_r($this->tranp->gedCard());
    }
}
