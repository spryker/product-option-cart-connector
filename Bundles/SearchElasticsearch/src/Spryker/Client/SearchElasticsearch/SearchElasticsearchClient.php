<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class SearchElasticsearchClient extends AbstractClient implements SearchElasticsearchClientInterface
{
    /**
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $resultFormatters
     * @param array $requestParameters
     *
     * @return array|\Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        return $this->getFactory()->createSearch()->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * @api
     *
     * @param string|null $indexName
     *
     * @return int
     */
    public function getTotalCount(?string $indexName = null): int
    {
    }

    /**
     * @api
     *
     * @param string|null $indexName
     *
     * @return array
     */
    public function getMetaData(?string $indexName = null): array
    {
    }

    /**
     * @api
     *
     * @param string $key
     * @param string $indexName
     *
     * @return mixed
     */
    public function read(string $key, string $indexName)
    {
    }

    /**
     * @api
     *
     * @param string|null $indexName
     *
     * @return bool
     */
    public function delete(?string $indexName = null): bool
    {
    }

    /**
     * @api
     *
     * @param array $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
    }
}
