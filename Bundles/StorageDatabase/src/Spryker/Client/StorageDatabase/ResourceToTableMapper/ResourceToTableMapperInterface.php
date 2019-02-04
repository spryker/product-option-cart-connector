<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\ResourceToTableMapper;

interface ResourceToTableMapperInterface
{
    /**
     * @param string $resourceKey
     *
     * @return string
     */
    public function map(string $resourceKey): string;
}
