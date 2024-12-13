<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\Postgres;

use Cycle\Schema\Tests\Relation\ManyToManyRelationCompositePKTest as BaseTest;

/**
 * @group driver
 * @group driver-postgres
 */
class ManyToManyRelationCompositePKTest extends BaseTest
{
    public const DRIVER = 'postgres';
}
