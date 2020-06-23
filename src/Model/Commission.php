<?php

namespace ProfitshareClient\Model;

use DateTime;

class Commission
{
    /**
     * @var int
     */
    public $order_id;

    /**
     * @var string
     */
    public $order_ref;

    /**
     * @var string
     */
    public $order_status;

    /**
     * @var int
     */
    public $advertiser_id;

    /**
     * @var string
     */
    public $hash;

    /**
     * @var DateTime
     */
    public $order_date;

    /**
     * @var DateTime
     */
    public $order_updated;

    /**
     * @var string
     */
    public $items_status;

    /**
     * @var string
     */
    public $items_commision;

    /**
     * @var string
     */
    public $items_commision_value;
}
