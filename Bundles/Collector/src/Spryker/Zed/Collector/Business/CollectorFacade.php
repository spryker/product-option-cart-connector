<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Collector\DatabaseCollectorInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Collector\Business\CollectorBusinessFactory getFactory()
 */
class CollectorFacade extends AbstractFacade implements CollectorFacadeInterface
{

    /**
     * @api
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface[]
     */
    public function exportStorage(OutputInterface $output)
    {
        $exporter = $this->getFactory()->createYvesStorageExporter();

        return $exporter->exportStorage($output);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface[]
     */
    public function exportStorageByLocale(LocaleTransfer $locale, OutputInterface $output)
    {
        $exporter = $this->getFactory()->createYvesStorageExporter();

        return $exporter->exportStorageByLocale($locale, $output);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface[]
     */
    public function exportSearchByLocale(LocaleTransfer $locale, OutputInterface $output)
    {
        $exporter = $this->getFactory()->createYvesSearchExporter();

        return $exporter->exportStorageByLocale($locale, $output);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Symfony\Component\Console\Output\OutputInterface|null $output
     *
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface[]
     */
    public function updateSearchByLocale(LocaleTransfer $locale, OutputInterface $output)
    {
        $exporter = $this->getFactory()->createYvesSearchUpdateExporter();

        return $exporter->exportStorageByLocale($locale, $output);
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getFactory()->createInstaller($messenger)->install();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getSearchIndexName()
    {
        return $this->getFactory()->getConfig()->getSearchIndexName();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getSearchDocumentType()
    {
        return $this->getFactory()->getConfig()->getSearchDocumentType();
    }

    /**
     * @api
     *
     * @param array $keys
     *
     * @return bool
     */
    public function deleteSearchTimestamps(array $keys = [])
    {
        return $this->getFactory()->createSearchMarker()->deleteTimestamps($keys);
    }

    /**
     * @api
     *
     * @param array $keys
     *
     * @return bool
     */
    public function deleteStorageTimestamps(array $keys = [])
    {
        return $this->getFactory()->createStorageMarker()->deleteTimestamps($keys);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getAllCollectorTypes()
    {
        $exporter = $this->getFactory()->createYvesStorageExporter();

        return $exporter->getAllCollectorTypes();
    }

    /**
     * @api
     *
     * @return array
     */
    public function getEnabledCollectorTypes()
    {
        $exporter = $this->getFactory()->createYvesStorageExporter();

        return $exporter->getEnabledCollectorTypes();
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Collector\Business\Collector\DatabaseCollectorInterface $collector *
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $baseQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $dataWriter
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function runCollector(
        DatabaseCollectorInterface $collector,
        SpyTouchQuery $baseQuery,
        LocaleTransfer $locale,
        BatchResultInterface $result,
        WriterInterface $dataWriter,
        TouchUpdaterInterface $touchUpdater,
        OutputInterface $output
    ) {

        $this->getFactory()
            ->getCollectorManager()
            ->runCollector(
                $collector,
                $baseQuery,
                $locale,
                $result,
                $dataWriter,
                $touchUpdater,
                $output
            );
    }

}
