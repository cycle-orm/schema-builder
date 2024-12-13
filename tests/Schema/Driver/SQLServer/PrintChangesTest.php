<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\SQLServer;

use Cycle\Schema\Tests\Generator\PrintChangesTest as BaseTest;

/**
 * @group driver
 * @group driver-sqlserver
 */
class PrintChangesTest extends BaseTest
{
    public const DRIVER = 'sqlserver';
}
