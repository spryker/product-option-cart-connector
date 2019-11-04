<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Persistence;

use Generated\Shared\Transfer\SalesOrderMerchantTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorPersistenceFactory getFactory()
 */
class SalesMerchantConnectorEntityManager extends AbstractEntityManager implements SalesMerchantConnectorEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderMerchantTransfer $salesOrderMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer
     */
    public function createSalesOrderMerchant(SalesOrderMerchantTransfer $salesOrderMerchantTransfer): SalesOrderMerchantTransfer
    {
        $salesMerchantConnectorMapper = $this->getFactory()->createSalesMerchantConnectorMapper();

        $salesOrderMerchantEntity = $this->getFactory()->createSalesOrderMerchantQuery()
            ->filterByMerchantReference($salesOrderMerchantTransfer->getMerchantReference())
            ->findOneOrCreate();

        $salesOrderMerchantEntity = $salesMerchantConnectorMapper->mapSalesOrderMerchantTransferToSalesOrderMerchantEntity(
            $salesOrderMerchantTransfer,
            $salesOrderMerchantEntity
        );

        $salesOrderMerchantEntity->save();

        return $salesMerchantConnectorMapper->mapSalesOrderMerchantEntityToSalesOrderMerchantTransfer(
            $salesOrderMerchantEntity,
            new SalesOrderMerchantTransfer()
        );
    }
}
