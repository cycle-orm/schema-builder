<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Driver\SQLite;

use Cycle\Schema\Compiler;
use Cycle\Schema\Generator\GenerateRelations;
use Cycle\Schema\Generator\RenderRelations;
use Cycle\Schema\Generator\RenderTables;
use Cycle\Schema\Registry;
use Cycle\Schema\Relation\HasOne;
use Cycle\Schema\Tests\Fixtures\Plain;
use Cycle\Schema\Tests\Fixtures\User;
use Cycle\Schema\Tests\Relation\HasOneRelationTest as BaseTest;

/**
 * @group driver
 * @group driver-sqlite
 */
class HasOneRelationTest extends BaseTest
{
    public const DRIVER = 'sqlite';

    public function testRenderWithoutIndex(): void
    {
        $e = Plain::define();
        $u = User::define();

        $u->getRelations()->get('plain')->getOptions()->set('indexCreate', false);

        $r = new Registry($this->dbal);
        $r->register($e)->linkTable($e, 'default', 'plain');
        $r->register($u)->linkTable($u, 'default', 'user');

        (new Compiler())->compile($r, [
            new GenerateRelations(['hasOne' => new HasOne()]),
            $t = new RenderTables(),
            new RenderRelations(),
        ]);

        $t->getReflector()->run();

        $table = $this->getDriver()->getSchema('plain');
        $this->assertFalse($table->hasIndex(['user_p_id']));
    }
}
