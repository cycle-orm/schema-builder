<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\MySQL;

use Cycle\Schema\Tests\Generator\PrintChangesTest as BaseTest;

/**
 * @group driver
 * @group driver-mysql
 */
class PrintChangesTest extends BaseTest
{
    public const DRIVER = 'mysql';
}
