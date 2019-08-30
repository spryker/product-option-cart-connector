<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch\ElasticsearchClient;

use Elastica\Client;

class ElasticsearchClientFactory implements ElasticsearchClientFactoryInterface
{
    /**
     * @var \Elastica\Client
     */
    protected static $client;

    /**
     * @param array $clientConfig
     *
     * @return \Elastica\Client
     */
    public function createClient(array $clientConfig): Client
    {
        if (!static::$client) {
            static::$client = (new Client($clientConfig));
        }

        return static::$client;
    }
}
