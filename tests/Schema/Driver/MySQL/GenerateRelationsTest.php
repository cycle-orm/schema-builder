<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\MySQL;

use Cycle\Schema\Tests\Generator\GenerateRelationsTest as BaseTest;

/**
 * @group driver
 * @group driver-mysql
 */
class GenerateRelationsTest extends BaseTest
{
    public const DRIVER = 'mysql';
}
