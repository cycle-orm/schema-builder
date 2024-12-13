<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\Postgres;

use Cycle\Schema\Tests\Generator\PrintChangesTest as BaseTest;

/**
 * @group driver
 * @group driver-postgres
 */
class PrintChangesTest extends BaseTest
{
    public const DRIVER = 'postgres';
}
