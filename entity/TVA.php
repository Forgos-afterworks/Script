<?php

class TVA {

    private int $idTVA;
    private float $prcentTVA;

    public function __construct() {
        $this->idTVA = 0;
        $this->prcentTVA = 20;
    }

    /**
     * @return int
     */
    public function getIdTVA(): int {
        return $this->idTVA;
    }

    /**
     * @return float|int
     */
    public function getPrcentTVA() {
        return $this->prcentTVA;
    }
}