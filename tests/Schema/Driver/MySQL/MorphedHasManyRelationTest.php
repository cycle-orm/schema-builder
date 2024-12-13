<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\MySQL;

use Cycle\Schema\Tests\Relation\Morphed\MorphedHasManyRelationTest as BaseTest;

/**
 * @group driver
 * @group driver-mysql
 */
class MorphedHasManyRelationTest extends BaseTest
{
    public const DRIVER = 'mysql';
}
