<?php

namespace Spryker\Zed\CompanyUser\Dependency;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserSavePluginInterface
{
    /**
     * @param CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function save(CompanyUserTransfer $companyUserTransfer);
}