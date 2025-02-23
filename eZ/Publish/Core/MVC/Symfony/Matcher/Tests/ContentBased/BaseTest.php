<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased;

use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use eZ\Publish\Core\Repository\Permission\PermissionResolver;
use eZ\Publish\Core\Repository\Mapper\RoleDomainMapper;
use eZ\Publish\SPI\Persistence\User\Handler as SPIUserHandler;
use eZ\Publish\Core\Repository\Permission\LimitationService;
use eZ\Publish\Core\MVC\Symfony\View\Provider\Location\Configured;
use eZ\Publish\Core\Repository\Repository;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    protected $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repositoryMock = $this->getRepositoryMock();
    }

    /**
     * @param array $matchingConfig
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPartiallyMockedViewProvider(array $matchingConfig = [])
    {
        return $this
            ->getMockBuilder(Configured::class)
            ->setConstructorArgs(
                [
                    $this->repositoryMock,
                    $matchingConfig,
                ]
            )
            ->setMethods(['getMatcher'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getRepositoryMock()
    {
        $repositoryClass = Repository::class;

        return $this
            ->getMockBuilder($repositoryClass)
            ->disableOriginalConstructor()
            ->setMethods(
                array_diff(
                    get_class_methods($repositoryClass),
                    ['sudo']
                )
            )
            ->getMock();
    }

    /**
     * @param array $properties
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getLocationMock(array $properties = [])
    {
        return $this
            ->getMockBuilder(Location::class)
            ->setConstructorArgs([$properties])
            ->getMockForAbstractClass();
    }

    /**
     * @param array $properties
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getContentInfoMock(array $properties = [])
    {
        return $this->
            getMockBuilder(ContentInfo::class)
            ->setConstructorArgs([$properties])
            ->getMockForAbstractClass();
    }

    protected function getPermissionResolverMock()
    {
        $configResolverMock = $this->createMock(ConfigResolverInterface::class);
        $configResolverMock
            ->method('getParameter')
            ->with('anonymous_user_id')
            ->willReturn(10);

        return $this
            ->getMockBuilder(PermissionResolver::class)
            ->setMethods(null)
            ->setConstructorArgs(
                [
                    $this->createMock(RoleDomainMapper::class),
                    $this->createMock(LimitationService::class),
                    $this->createMock(SPIUserHandler::class),
                    $configResolverMock,
                    [],
                ]
            )
            ->getMock();
    }
}
