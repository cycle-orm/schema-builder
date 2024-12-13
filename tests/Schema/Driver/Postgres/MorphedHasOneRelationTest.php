<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\Postgres;

use Cycle\Schema\Tests\Relation\Morphed\MorphedHasOneRelationTest as BaseTest;

/**
 * @group driver
 * @group driver-postgres
 */
class MorphedHasOneRelationTest extends BaseTest
{
    public const DRIVER = 'postgres';
}
