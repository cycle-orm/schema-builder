<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\SQLite;

use Cycle\Schema\Tests\Generator\ResolveInterfacesTest as BaseTest;

/**
 * @group driver
 * @group driver-sqlite
 */
class ResolveInterfacesTest extends BaseTest
{
    public const DRIVER = 'sqlite';
}
