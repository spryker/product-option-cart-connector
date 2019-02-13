<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use ArrayObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 *
 * @deprecated Testing transfer object deprecation.
 */
class MergedDeprecatedFooBarTransfer extends AbstractTransfer
{
    /**
     * @deprecated scalarField is deprecated.
     */
    public const SCALAR_FIELD = 'scalarField';

    /**
     * @deprecated arrayField is deprecated.
     */
    public const ARRAY_FIELD = 'arrayField';

    /**
     * @deprecated transferField is deprecated.
     */
    public const TRANSFER_FIELD = 'transferField';

    /**
     * @deprecated transferCollectionField is deprecated.
     */
    public const TRANSFER_COLLECTION_FIELD = 'transferCollectionField';

    /**
     * @deprecated Deprecated on project level.
     */
    public const PROJECT_LEVEL_DEPRECATED_FIELD = 'projectLevelDeprecatedField';

    /**
     * @var string|null
     */
    protected $scalarField;

    /**
     * @var array
     */
    protected $arrayField = [];

    /**
     * @var \Generated\Shared\Transfer\DeprecatedFooBarTransfer|null
     */
    protected $transferField;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\DeprecatedFooBarTransfer[]
     */
    protected $transferCollectionField;

    /**
     * @var string|null
     */
    protected $projectLevelDeprecatedField;

    /**
     * @var array
     */
    protected $transferPropertyNameMap = [
        'scalar_field' => 'scalarField',
        'scalarField' => 'scalarField',
        'ScalarField' => 'scalarField',
        'array_field' => 'arrayField',
        'arrayField' => 'arrayField',
        'ArrayField' => 'arrayField',
        'transfer_field' => 'transferField',
        'transferField' => 'transferField',
        'TransferField' => 'transferField',
        'transfer_collection_field' => 'transferCollectionField',
        'transferCollectionField' => 'transferCollectionField',
        'TransferCollectionField' => 'transferCollectionField',
        'project_level_deprecated_field' => 'projectLevelDeprecatedField',
        'projectLevelDeprecatedField' => 'projectLevelDeprecatedField',
        'ProjectLevelDeprecatedField' => 'projectLevelDeprecatedField',
    ];

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::SCALAR_FIELD => [
            'type' => 'string',
            'name_underscore' => 'scalar_field',
            'is_collection' => false,
            'is_transfer' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
        ],
        self::ARRAY_FIELD => [
            'type' => 'array',
            'name_underscore' => 'array_field',
            'is_collection' => false,
            'is_transfer' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
        ],
        self::TRANSFER_FIELD => [
            'type' => 'Generated\Shared\Transfer\DeprecatedFooBarTransfer',
            'name_underscore' => 'transfer_field',
            'is_collection' => false,
            'is_transfer' => true,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
        ],
        self::TRANSFER_COLLECTION_FIELD => [
            'type' => 'Generated\Shared\Transfer\DeprecatedFooBarTransfer',
            'name_underscore' => 'transfer_collection_field',
            'is_collection' => true,
            'is_transfer' => true,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
        ],
        self::PROJECT_LEVEL_DEPRECATED_FIELD => [
            'type' => 'string',
            'name_underscore' => 'project_level_deprecated_field',
            'is_collection' => false,
            'is_transfer' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
        ],
    ];

    /**
     * @module Deprecated
     *
     * @deprecated scalarField is deprecated.
     *
     * @param string|null $scalarField
     *
     * @return $this
     */
    public function setScalarField($scalarField)
    {
        $this->scalarField = $scalarField;
        $this->modifiedProperties[self::SCALAR_FIELD] = true;

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated scalarField is deprecated.
     *
     * @return string|null
     */
    public function getScalarField()
    {
        return $this->scalarField;
    }

    /**
     * @module Deprecated
     *
     * @deprecated scalarField is deprecated.
     *
     * @return $this
     */
    public function requireScalarField()
    {
        $this->assertPropertyIsSet(self::SCALAR_FIELD);

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated arrayField is deprecated.
     *
     * @param array|null $arrayField
     *
     * @return $this
     */
    public function setArrayField(array $arrayField = null)
    {
        if ($arrayField === null) {
            $arrayField = [];
        }

        $this->arrayField = $arrayField;
        $this->modifiedProperties[self::ARRAY_FIELD] = true;

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated arrayField is deprecated.
     *
     * @return array
     */
    public function getArrayField()
    {
        return $this->arrayField;
    }

    /**
     * @module Deprecated
     *
     * @deprecated arrayField is deprecated.
     *
     * @param mixed $arrayField
     *
     * @return $this
     */
    public function addArrayField($arrayField)
    {
        $this->arrayField[] = $arrayField;
        $this->modifiedProperties[self::ARRAY_FIELD] = true;

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated arrayField is deprecated.
     *
     * @return $this
     */
    public function requireArrayField()
    {
        $this->assertPropertyIsSet(self::ARRAY_FIELD);

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated transferField is deprecated.
     *
     * @param \Generated\Shared\Transfer\DeprecatedFooBarTransfer|null $transferField
     *
     * @return $this
     */
    public function setTransferField(DeprecatedFooBarTransfer $transferField = null)
    {
        $this->transferField = $transferField;
        $this->modifiedProperties[self::TRANSFER_FIELD] = true;

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated transferField is deprecated.
     *
     * @return \Generated\Shared\Transfer\DeprecatedFooBarTransfer|null
     */
    public function getTransferField()
    {
        return $this->transferField;
    }

    /**
     * @module Deprecated
     *
     * @deprecated transferField is deprecated.
     *
     * @return $this
     */
    public function requireTransferField()
    {
        $this->assertPropertyIsSet(self::TRANSFER_FIELD);

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated transferCollectionField is deprecated.
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\DeprecatedFooBarTransfer[] $transferCollectionField
     *
     * @return $this
     */
    public function setTransferCollectionField(ArrayObject $transferCollectionField)
    {
        $this->transferCollectionField = $transferCollectionField;
        $this->modifiedProperties[self::TRANSFER_COLLECTION_FIELD] = true;

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated transferCollectionField is deprecated.
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DeprecatedFooBarTransfer[]
     */
    public function getTransferCollectionField()
    {
        return $this->transferCollectionField;
    }

    /**
     * @module Deprecated
     *
     * @deprecated transferCollectionField is deprecated.
     *
     * @param \Generated\Shared\Transfer\DeprecatedFooBarTransfer $transferCollectionField
     *
     * @return $this
     */
    public function addTransferCollectionField(DeprecatedFooBarTransfer $transferCollectionField)
    {
        $this->transferCollectionField[] = $transferCollectionField;
        $this->modifiedProperties[self::TRANSFER_COLLECTION_FIELD] = true;

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated transferCollectionField is deprecated.
     *
     * @return $this
     */
    public function requireTransferCollectionField()
    {
        $this->assertCollectionPropertyIsSet(self::TRANSFER_COLLECTION_FIELD);

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated Deprecated on project level.
     *
     * @param string|null $projectLevelDeprecatedField
     *
     * @return $this
     */
    public function setProjectLevelDeprecatedField($projectLevelDeprecatedField)
    {
        $this->projectLevelDeprecatedField = $projectLevelDeprecatedField;
        $this->modifiedProperties[self::PROJECT_LEVEL_DEPRECATED_FIELD] = true;

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated Deprecated on project level.
     *
     * @return string|null
     */
    public function getProjectLevelDeprecatedField()
    {
        return $this->projectLevelDeprecatedField;
    }

    /**
     * @module Deprecated
     *
     * @deprecated Deprecated on project level.
     *
     * @return $this
     */
    public function requireProjectLevelDeprecatedField()
    {
        $this->assertPropertyIsSet(self::PROJECT_LEVEL_DEPRECATED_FIELD);

        return $this;
    }
}
