<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Bundle\EzPublishCoreBundle\Imagine\Filter;

use Imagine\Exception\NotSupportedException;
use Imagine\Image\ImageInterface;

class UnsupportedFilter extends AbstractFilter
{
    /**
     * @throws \Imagine\Exception\NotSupportedException
     */
    public function apply(ImageInterface $image)
    {
        throw new NotSupportedException('The filter is not supported by your current configuration.');
    }
}
