<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\CriteriaBuilder;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\HasOffersProductTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\IsActiveProductTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface;

class ProductTableCriteriaBuilder implements ProductTableCriteriaBuilderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var string|null
     */
    protected $searchTerm;

    /**
     * @var array
     */
    protected $sorting;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $pageSize;

    /**
     * @var array
     */
    protected $filters;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductOfferGuiPageToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferGuiPageToLocaleFacadeInterface $localeFacade
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductTableCriteriaTransfer
     */
    public function build(): ProductTableCriteriaTransfer
    {
        $productTableCriteriaTransfer = $this->buildProductTableCriteriaTransfer();
        $productTableCriteriaTransfer->setSearchTerm($this->searchTerm);
        $productTableCriteriaTransfer->setOrderBy($this->sorting);
        $productTableCriteriaTransfer->setPagination($this->buildPaginationTransfer());
        $productTableCriteriaTransfer = $this->addFilterData($productTableCriteriaTransfer);

        return $productTableCriteriaTransfer;
    }

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setPage(int $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param int $pageSize
     *
     * @return $this
     */
    public function setPageSize(int $pageSize)
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * @param string|null $searchTerm
     *
     * @return $this
     */
    public function setSearchTerm(?string $searchTerm)
    {
        $this->searchTerm = $searchTerm;

        return $this;
    }

    /**
     * @param string[] $sorting
     *
     * @return $this
     */
    public function setSorting(array $sorting)
    {
        $this->sorting = $sorting;

        return $this;
    }

    /**
     * @param array $filters
     *
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductTableCriteriaTransfer
     */
    protected function buildProductTableCriteriaTransfer(): ProductTableCriteriaTransfer
    {
        $productTableCriteriaTransfer = new ProductTableCriteriaTransfer();
        $productTableCriteriaTransfer->setMerchantUser($this->merchantUserFacade->getCurrentMerchantUser());
        $productTableCriteriaTransfer->setLocale($this->localeFacade->getCurrentLocale());

        return $productTableCriteriaTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function buildPaginationTransfer(): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setPage($this->page)
            ->setMaxPerPage($this->pageSize);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableCriteriaTransfer
     */
    protected function addFilterData(ProductTableCriteriaTransfer $productTableCriteriaTransfer): ProductTableCriteriaTransfer
    {
        if (!$this->filters) {
            return $productTableCriteriaTransfer;
        }

        if (isset($this->filters[IsActiveProductTableFilterDataProvider::FILTER_NAME])) {
            $isActive = (bool)$this->filters[IsActiveProductTableFilterDataProvider::FILTER_NAME];
            $productTableCriteriaTransfer->setIsActive($isActive);
        }

        if (isset($this->filters[HasOffersProductTableFilterDataProvider::FILTER_NAME])) {
            $hasOffers = (bool)$this->filters[HasOffersProductTableFilterDataProvider::FILTER_NAME];
            $productTableCriteriaTransfer->setHasOffers($hasOffers);
        }

        return $productTableCriteriaTransfer;
    }
}
