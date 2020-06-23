<?php

namespace ProfitshareClient\Model;

class Paginator
{
    /**
     * @var int
     */
    public $itemsPerPage;

    /**
     * @var int
     */
    public $currentPage;

    /**
     * @var int
     */
    public $totalPages;

    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->totalPages;
    }

    public function getNextPage(): int
    {
        return ++$this->currentPage;
    }
}
