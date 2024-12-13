<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\SQLServer;

use Cycle\Schema\Tests\Relation\HasManyRelationCompositePKTest as BaseTest;

/**
 * @group driver
 * @group driver-sqlserver
 */
class HasManyRelationCompositePKTest extends BaseTest
{
    public const DRIVER = 'sqlserver';
}
