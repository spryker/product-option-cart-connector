<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemBulkOperationValidatorInterface;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemOperationValidatorInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListItemOperation implements ShoppingListItemOperationInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface
     */
    protected $shoppingListEntityManager;

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface
     */
    protected $shoppingListRepository;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemBulkOperationValidatorInterface
     */
    protected $addOperationValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemOperationValidatorInterface
     */
    protected $updateOperationValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemOperationValidatorInterface
     */
    protected $deleteOperationValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface
     */
    protected $pluginExecutor;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface $shoppingListEntityManager
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemBulkOperationValidatorInterface $addOperationValidator ,
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemOperationValidatorInterface $updateOperationValidator ,
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemOperationValidatorInterface $deleteOperationValidator ,
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface $pluginExecutor
     */
    public function __construct(
        ShoppingListEntityManagerInterface $shoppingListEntityManager,
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListItemBulkOperationValidatorInterface $addOperationValidator,
        ShoppingListItemOperationValidatorInterface $updateOperationValidator,
        ShoppingListItemOperationValidatorInterface $deleteOperationValidator,
        ShoppingListItemPluginExecutorInterface $pluginExecutor
    ) {
        $this->shoppingListEntityManager = $shoppingListEntityManager;
        $this->shoppingListRepository = $shoppingListRepository;
        $this->addOperationValidator = $addOperationValidator;
        $this->updateOperationValidator = $updateOperationValidator;
        $this->deleteOperationValidator = $deleteOperationValidator;
        $this->pluginExecutor = $pluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function addShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListItemResponseTransfer = new ShoppingListItemResponseTransfer();

        if (!$this->addOperationValidator->validateRequest(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        )) {
            return $shoppingListItemResponseTransfer;
        }

        return $this->addOperationValidator->invalidateResponse(
            $this->saveShoppingListItemTransfer($shoppingListItemTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function updateShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListItemResponseTransfer = new ShoppingListItemResponseTransfer();

        if (!$this->updateOperationValidator->validateRequest(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        )) {
            return $shoppingListItemResponseTransfer;
        }

        return $this->updateOperationValidator->invalidateResponse(
            $this->saveShoppingListItemTransfer($shoppingListItemTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->addShoppingListItem($shoppingListItemTransfer)->getShoppingListItem() ?? $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function deleteShoppingListItems(ShoppingListTransfer $shoppingListTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListTransfer) {
            $this->executeDeleteShoppingListItemsTransaction($shoppingListTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function addItems(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = new ShoppingListResponseTransfer();

        if (!$this->addOperationValidator->validateBulkRequest(
            $shoppingListTransfer,
            $shoppingListResponseTransfer
        )) {
            return $shoppingListResponseTransfer;
        }

        $shoppingListTransfer = $shoppingListResponseTransfer->getShoppingList() ?? $shoppingListTransfer;

        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListTransfer) {
            return $this->executeAddItemsTransaction($shoppingListTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    protected function executeAddItemsTransaction(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $this->createItems($shoppingListTransfer);

        $response = (new ShoppingListResponseTransfer())
            ->setIsSuccess(true)
            ->setShoppingList($shoppingListTransfer);

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    protected function createItems(ShoppingListTransfer $shoppingListTransfer): void
    {
        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemTransfer = $this->shoppingListEntityManager->saveShoppingListItem($shoppingListItemTransfer);

            $this->addOperationValidator->invalidateResponse(
                (new ShoppingListItemResponseTransfer())
                    ->setShoppingListItem($shoppingListItemTransfer)
                    ->setIsSuccess(true)
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function removeItemById(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer
    {
        $shoppingListItemResponseTransfer = new ShoppingListItemResponseTransfer();

        if (!$this->deleteOperationValidator->validateRequest(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        )) {
            return $shoppingListItemResponseTransfer;
        }

        return $this->deleteOperationValidator->invalidateResponse(
            $this->deleteShoppingListItem($shoppingListItemTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->updateShoppingListItem($shoppingListItemTransfer)->getShoppingListItem() ?? $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItemWithoutPermissionsCheck(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->saveShoppingListItemTransfer($shoppingListItemTransfer)->getShoppingListItem() ?? $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function deleteShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer
    {
        $shoppingListItemTransfer = $this->pluginExecutor->executeItemExpanderPlugins($shoppingListItemTransfer);

        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListItemTransfer) {
            return $this->deleteShoppingListItemTransaction($shoppingListItemTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    protected function saveShoppingListItemTransfer(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListItemTransfer) {
            return $this->saveShoppingListItemTransaction($shoppingListItemTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    protected function executeDeleteShoppingListItemsTransaction(ShoppingListTransfer $shoppingListTransfer): void
    {
        $shoppingListItemCollectionTransfer = $this->shoppingListRepository
            ->findShoppingListItemsByIdShoppingList($shoppingListTransfer->getIdShoppingList());

        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $this->deleteShoppingListItem($shoppingListItemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    protected function saveShoppingListItemTransaction(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListItemTransfer = $this->shoppingListEntityManager->saveShoppingListItem($shoppingListItemTransfer);
        $this->pluginExecutor->executePostSavePlugins($shoppingListItemTransfer);

        return (new ShoppingListItemResponseTransfer())
            ->setShoppingListItem($shoppingListItemTransfer)
            ->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    protected function deleteShoppingListItemTransaction(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemResponseTransfer {
        $this->pluginExecutor->executeBeforeDeletePlugins($shoppingListItemTransfer);
        $this->shoppingListEntityManager->deleteShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

        return (new ShoppingListItemResponseTransfer())->setIsSuccess(true);
    }
}
