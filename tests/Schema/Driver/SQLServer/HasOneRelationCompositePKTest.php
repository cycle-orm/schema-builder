<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\SQLServer;

use Cycle\Schema\Tests\Relation\HasOneRelationCompositePKTest as BaseTest;

/**
 * @group driver
 * @group driver-sqlserver
 */
class HasOneRelationCompositePKTest extends BaseTest
{
    public const DRIVER = 'sqlserver';
}
