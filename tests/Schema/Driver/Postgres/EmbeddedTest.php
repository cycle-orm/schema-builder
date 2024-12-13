<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\Postgres;

use Cycle\Schema\Tests\Relation\EmbeddedTest as BaseTest;

/**
 * @group driver
 * @group driver-postgres
 */
class EmbeddedTest extends BaseTest
{
    public const DRIVER = 'postgres';
}
