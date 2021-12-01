<?php

namespace ProfitshareClient\Model;

class Campaign
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $commissionType;

    /**
     * @var \DateTime
     */
    public $startDate;

    /**
     * @var \DateTime
     */
    public $endDate;

    /**
     * @var string
     */
    public $url;

    /**
     * @var int
     */
    public $advertiser_id;

    /**
     * @var array
     */
    public $banners;
}
