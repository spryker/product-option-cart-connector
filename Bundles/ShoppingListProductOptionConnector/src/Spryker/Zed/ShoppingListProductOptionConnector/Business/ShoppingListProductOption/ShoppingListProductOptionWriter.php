<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface;

class ShoppingListProductOptionWriter implements ShoppingListProductOptionWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface
     */
    protected $shoppingListProductOptionEntityManager;

    /**
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface $shoppingListProductOptionEntityManager
     */
    public function __construct(
        ShoppingListProductOptionConnectorEntityManagerInterface $shoppingListProductOptionEntityManager
    ) {
        $this->shoppingListProductOptionEntityManager = $shoppingListProductOptionEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItemProductOptions(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListItemTransfer) {
            $this->executeSaveShoppingListItemProductOptionsTransaction($shoppingListItemTransfer);
        });

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemTransfers
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function saveShoppingListItemProductOptionsBulk(ShoppingListItemCollectionTransfer $shoppingListItemTransfers): ShoppingListItemCollectionTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListItemTransfers) {
            $result = new ShoppingListItemCollectionTransfer();
            foreach ($shoppingListItemTransfers->getItems() as $shoppingListItemTransfer) {
                $this->executeSaveShoppingListItemProductOptionsTransaction($shoppingListItemTransfer);
                $result->addItem($shoppingListItemTransfer);
            }

            return $result;
        });
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function removeShoppingListItemProductOptions(int $idShoppingListItem): void
    {
        $this->shoppingListProductOptionEntityManager
            ->removeShoppingListItemProductOptions($idShoppingListItem);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    protected function executeSaveShoppingListItemProductOptionsTransaction(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $shoppingListItemTransfer->requireIdShoppingListItem();

        $this->removeShoppingListItemProductOptions($shoppingListItemTransfer->getIdShoppingListItem());

        foreach ($shoppingListItemTransfer->getProductOptions() as $productOptionTransfer) {
            $this->shoppingListProductOptionEntityManager
                ->saveShoppingListItemProductOption(
                    $shoppingListItemTransfer->getIdShoppingListItem(),
                    $productOptionTransfer->getIdProductOptionValue()
                );
        }
    }
}
