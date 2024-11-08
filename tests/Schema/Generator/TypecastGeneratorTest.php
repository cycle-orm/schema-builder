<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests\Generator;

use Cycle\ORM\Schema;
use Cycle\Schema\Compiler;
use Cycle\Schema\Generator\GenerateTypecast;
use Cycle\Schema\Generator\RenderTables;
use Cycle\Schema\Registry;
use Cycle\Schema\Tests\BaseTest;
use Cycle\Schema\Tests\Fixtures\User;

abstract class TypecastGeneratorTest extends BaseTest
{
    public function testCompiledUser(): void
    {
        $e = User::define();

        $r = new Registry($this->dbal);
        $r->register($e)->linkTable($e, 'default', 'user');

        $c = new Compiler();
        $schema = $c->compile($r, [new RenderTables(), new GenerateTypecast()]);


        $this->assertSame('int', $schema['user'][Schema::TYPECAST]['p_id']);
        $this->assertSame('float', $schema['user'][Schema::TYPECAST]['p_balance']);
        $this->assertSame('datetime', $schema['user'][Schema::TYPECAST]['p_created_at']);
        $this->assertSame('string', $schema['user'][Schema::TYPECAST]['p_name']);

        $this->assertTrue(in_array($schema['user'][Schema::TYPECAST]['p_id'], ['int', 'bool']));
    }
}
