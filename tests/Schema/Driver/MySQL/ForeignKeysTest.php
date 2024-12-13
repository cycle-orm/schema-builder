<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\MySQL;

use Cycle\Schema\Tests\Generator\ForeignKeysTest as BaseTest;

/**
 * @group driver
 * @group driver-mysql
 */
class ForeignKeysTest extends BaseTest
{
    public const DRIVER = 'mysql';
}
