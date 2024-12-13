<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\SQLite;

use Cycle\Schema\Tests\Relation\BelongsToRelationCompositePKTest as BaseTest;

/**
 * @group driver
 * @group driver-sqlite
 */
class BelongsToRelationCompositePKTest extends BaseTest
{
    public const DRIVER = 'sqlite';
}
