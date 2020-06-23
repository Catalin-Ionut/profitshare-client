<?php

namespace ProfitshareClient\Model;

class Products
{
    /**
     * @var int
     */
    public $current_page;

    /**
     * @var int
     */
    public $total_pages;

    /**
     * @var int
     */
    public $records_per_page;

    /**
     * @var Product[]
     */
    public $products;
}
