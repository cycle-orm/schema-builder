<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\SQLite;

use Cycle\Schema\Tests\Relation\Morphed\MorphedHasManyRelationTest as BaseTest;

/**
 * @group driver
 * @group driver-sqlite
 */
class MorphedHasManyRelationTest extends BaseTest
{
    public const DRIVER = 'sqlite';
}
