<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\FacetAggregatedQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\FacetFilteredQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\PaginatedQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SortedQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\FacetResultFormatterPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\PaginatedResultFormatterPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\SortedResultFormatterPlugin;

class CatalogDependencyProvider extends AbstractDependencyProvider
{

    const KVSTORAGE = 'kvstorage';
    const CLIENT_SEARCH = 'search client';
    const CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS = 'catalog search query expander plugins';
    const CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS = 'catalog search result formatter plugins';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container[self::KVSTORAGE] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };

        $container[self::CLIENT_SEARCH] = function (Container $container) {
            return $container->getLocator()->search()->client();
        };

        $container[self::CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS] = function (Container $container) {
            return $this->createCatalogSearchQueryExpanderPlugins($container);
        };

        $container[self::CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS] = function (Container $container) {
            return $this->createCatalogSearchResultFormatterPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Search\Plugin\QueryExpanderPluginInterface[]
     */
    protected function createCatalogSearchQueryExpanderPlugins(Container $container)
    {
        return [
            new SortedQueryExpanderPlugin(),
            new FacetAggregatedQueryExpanderPlugin(),
            new FacetFilteredQueryExpanderPlugin(),
            new PaginatedQueryExpanderPlugin(),
        ];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Search\Plugin\ResultFormatterPluginInterface[]
     */
    protected function createCatalogSearchResultFormatterPlugins(Container $container)
    {
        return [
            new FacetResultFormatterPlugin(),
            new PaginatedResultFormatterPlugin(),
            new SortedResultFormatterPlugin(),
        ];
    }

}
