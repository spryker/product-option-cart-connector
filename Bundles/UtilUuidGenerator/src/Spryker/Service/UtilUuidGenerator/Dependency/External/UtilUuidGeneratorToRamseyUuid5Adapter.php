<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUuidGenerator\Dependency\External;

use Ramsey\Uuid\Uuid;

class UtilUuidGeneratorToRamseyUuid5Adapter implements UtilUuidGeneratorToUuid5GeneratorInterface
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function uuid5(string $name): string
    {
        return Uuid::uuid5(Uuid::NAMESPACE_OID, $name)->toString();
    }
}
