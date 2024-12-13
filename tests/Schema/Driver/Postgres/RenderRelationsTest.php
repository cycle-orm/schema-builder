<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\Postgres;

use Cycle\Schema\Tests\Generator\RenderRelationsTest as BaseTest;

/**
 * @group driver
 * @group driver-postgres
 */
class RenderRelationsTest extends BaseTest
{
    public const DRIVER = 'postgres';
}
