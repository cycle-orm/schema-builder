<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\MySQL;

use Cycle\Schema\Tests\Relation\Morphed\MorphedHasOneRelationCompositePKTest as BaseTest;

/**
 * @group driver
 * @group driver-mysql
 */
class MorphedHasOneRelationCompositePKTest extends BaseTest
{
    public const DRIVER = 'mysql';
}
