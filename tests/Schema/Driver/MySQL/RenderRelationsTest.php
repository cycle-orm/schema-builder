<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\MySQL;

use Cycle\Schema\Tests\Generator\RenderRelationsTest as BaseTest;

/**
 * @group driver
 * @group driver-mysql
 */
class RenderRelationsTest extends BaseTest
{
    public const DRIVER = 'mysql';
}
