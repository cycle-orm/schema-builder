<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\SQLServer;

use Cycle\Schema\Tests\Relation\BelongsToRelationCompositePKTest as BaseTest;

/**
 * @group driver
 * @group driver-sqlserver
 */
class BelongsToRelationCompositePKTest extends BaseTest
{
    public const DRIVER = 'sqlserver';
}
