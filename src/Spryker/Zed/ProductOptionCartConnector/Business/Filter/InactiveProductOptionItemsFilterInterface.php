<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Filter;

use Generated\Shared\Transfer\CartChangeTransfer;

interface InactiveProductOptionItemsFilterInterface
{
    public function filterOutInactiveProductOptionCartChangeItems(
        CartChangeTransfer $cartChangeTransfer
    ): CartChangeTransfer;
}
