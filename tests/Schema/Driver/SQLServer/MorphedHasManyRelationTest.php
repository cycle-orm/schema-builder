<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\SQLServer;

use Cycle\Schema\Tests\Relation\Morphed\MorphedHasManyRelationTest as BaseTest;

/**
 * @group driver
 * @group driver-sqlserver
 */
class MorphedHasManyRelationTest extends BaseTest
{
    public const DRIVER = 'sqlserver';
}
