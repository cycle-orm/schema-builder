<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\MySQL;

use Cycle\Schema\Tests\Relation\Morphed\MorphedHasOneRelationTest as BaseTest;

/**
 * @group driver
 * @group driver-mysql
 */
class MorphedHasOneRelationTest extends BaseTest
{
    public const DRIVER = 'mysql';
}
