<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace eZ\Publish\API\Repository\Values\ContentType;

use eZ\Publish\API\Repository\Values\ValueObject;

abstract class ContentTypeGroupStruct extends ValueObject
{
    /**
     * Readable and unique string identifier of a group.
     *
     * @var string
     */
    public $identifier;
}
