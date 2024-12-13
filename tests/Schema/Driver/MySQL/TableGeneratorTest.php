<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\MySQL;

use Cycle\Schema\Tests\Generator\TableGeneratorTest as BaseTest;

/**
 * @group driver
 * @group driver-mysql
 */
class TableGeneratorTest extends BaseTest
{
    public const DRIVER = 'mysql';
}
