<?php

declare(strict_types=1);

namespace App\Resource;

use Pagerfanta\Pagerfanta;

final class PaginationResource
{
    /**
     * @var int
     */
    private $totalNumberOfResults;

    /**
     * @var int
     */
    private $resultsPerPageCount;

    /**
     * @var int
     */
    private $currentPageNumber;
    
    /**
     * @var int
     */
    private $totalPageNumber;

    /**
     * PaginationResource constructor.
     *
     * @param int $totalNumberOfResults
     * @param int $resultsPerPageCount
     * @param int $currentPageNumber
     */
    public function __construct(int $totalNumberOfResults = 0, int $resultsPerPageCount = 0, int $currentPageNumber = 0, int $totalPageNumber)
    {
        $this->totalNumberOfResults = $totalNumberOfResults;
        $this->resultsPerPageCount = $resultsPerPageCount;
        $this->currentPageNumber = $currentPageNumber;
        $this->totalPageNumber = $totalPageNumber;
    }

    /**
     * @param Pagerfanta $paginator
     *
     * @return self
     */
    public static function createFromPagerfanta(Pagerfanta $paginator): self
    {
        return new self(
            $paginator->getNbResults(),
            $paginator->getMaxPerPage(),
            $paginator->getCurrentPage(),
            $paginator->getNbPages()
        );
    }

    /**
     * @return array
     */
    public function toJsArray(): array
    {
        return [
            'total' => $this->totalNumberOfResults,
            'limit' => $this->resultsPerPageCount,
            'page' => $this->currentPageNumber,
            'total_page' => $this->totalPageNumber
        ];
    }
}
