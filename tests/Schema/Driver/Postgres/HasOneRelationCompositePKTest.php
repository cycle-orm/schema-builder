<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\Postgres;

use Cycle\Schema\Tests\Relation\HasOneRelationCompositePKTest as BaseTest;

/**
 * @group driver
 * @group driver-postgres
 */
class HasOneRelationCompositePKTest extends BaseTest
{
    public const DRIVER = 'postgres';
}
