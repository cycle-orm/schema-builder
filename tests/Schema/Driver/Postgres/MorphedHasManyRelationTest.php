<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\Postgres;

use Cycle\Schema\Tests\Relation\Morphed\MorphedHasManyRelationTest as BaseTest;

/**
 * @group driver
 * @group driver-postgres
 */
class MorphedHasManyRelationTest extends BaseTest
{
    public const DRIVER = 'postgres';
}
