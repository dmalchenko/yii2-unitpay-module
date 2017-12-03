<?php

namespace dmalchenko\unitpay\models;


class CashItem {
    private $name;
    private $count;
    private $price;

    /**
     * @param string $name
     * @param int $count
     * @param float $price
     */
    public function __construct($name, $count, $price) {
        $this->name = $name;
        $this->count = $count;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCount() {
        return $this->count;
    }

    /**
     * @return float
     */
    public function getPrice() {
        return $this->price;
    }
}