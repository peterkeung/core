<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace eZ\Publish\API\Repository\Events\Role;

use eZ\Publish\API\Repository\Values\User\PolicyCreateStruct;
use eZ\Publish\API\Repository\Values\User\RoleDraft;
use eZ\Publish\SPI\Repository\Event\BeforeEvent;
use UnexpectedValueException;

final class BeforeAddPolicyByRoleDraftEvent extends BeforeEvent
{
    /** @var \eZ\Publish\API\Repository\Values\User\RoleDraft */
    private $roleDraft;

    /** @var \eZ\Publish\API\Repository\Values\User\PolicyCreateStruct */
    private $policyCreateStruct;

    /** @var \eZ\Publish\API\Repository\Values\User\RoleDraft|null */
    private $updatedRoleDraft;

    public function __construct(RoleDraft $roleDraft, PolicyCreateStruct $policyCreateStruct)
    {
        $this->roleDraft = $roleDraft;
        $this->policyCreateStruct = $policyCreateStruct;
    }

    public function getRoleDraft(): RoleDraft
    {
        return $this->roleDraft;
    }

    public function getPolicyCreateStruct(): PolicyCreateStruct
    {
        return $this->policyCreateStruct;
    }

    public function getUpdatedRoleDraft(): RoleDraft
    {
        if (!$this->hasUpdatedRoleDraft()) {
            throw new UnexpectedValueException(sprintf('Return value is not set or not of type %s. Check hasUpdatedRoleDraft() or set it using setUpdatedRoleDraft() before you call the getter.', RoleDraft::class));
        }

        return $this->updatedRoleDraft;
    }

    public function setUpdatedRoleDraft(?RoleDraft $updatedRoleDraft): void
    {
        $this->updatedRoleDraft = $updatedRoleDraft;
    }

    public function hasUpdatedRoleDraft(): bool
    {
        return $this->updatedRoleDraft instanceof RoleDraft;
    }
}
