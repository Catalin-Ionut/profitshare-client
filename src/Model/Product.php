<?php

namespace ProfitshareClient\Model;

class Product
{
    /**
     * @var int
     */
    public $advertiser_id;

    /**
     * @var string
     */
    public $advertiser_name;

    /**
     * @var string
     */
    public $category_name;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $link;

    /**
     * @var string
     */
    public $image;

    /**
     * @var float
     */
    public $price;

    /**
     * @var float
     */
    public $price_vat;

    /**
     * @var float
     */
    public $price_discounted;

    /**
     * @var string
     */
    public $affiliate_link;
}
