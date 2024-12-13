<?php

declare(strict_types=1);

namespace Cycle\Schema\Tests;

use Cycle\Schema\Compiler;
use Cycle\Schema\Definition\Entity;
use Cycle\Schema\Definition\Field;
use Cycle\Schema\Exception\RegistryException;
use Cycle\Schema\Registry;
use Cycle\Schema\Tests\Fixtures\Author;
use Cycle\Schema\Tests\Fixtures\Post;
use Cycle\Schema\Tests\Fixtures\User;

abstract class RegistryTest extends BaseTest
{
    public function testHasRole(): void
    {
        $r = new Registry($this->dbal);
        $e = new Entity();
        $e->setRole('user')->setClass(User::class);

        $r->register($e);

        $this->assertTrue($r->hasEntity('user'));
        $this->assertTrue($r->hasEntity(User::class));

        $this->assertFalse($r->hasEntity('post'));
        $this->assertFalse($r->hasEntity(Post::class));
    }

    public function testDuplicateRoleShouldThrowAnException(): void
    {
        $this->expectException(RegistryException::class);
        $this->expectExceptionMessage('Duplicate entity `user`');

        $r = new Registry($this->dbal);

        $e = new Entity();
        $e->setRole('user')->setClass(User::class);

        $e2 = new Entity();
        $e2->setRole('user')->setClass(Author::class);


        $r->register($e);
        $r->register($e2);
    }

    public function testGetEntity(): void
    {
        $r = new Registry($this->dbal);
        $e = new Entity();
        $e->setRole('user')->setClass(User::class);

        $r->register($e);

        $this->assertSame($e, $r->getEntity('user'));
    }

    public function testGetEntityException(): void
    {
        $r = new Registry($this->dbal);

        $this->expectException(RegistryException::class);

        $r->getEntity('user');
    }

    public function testLinkTableException(): void
    {
        $r = new Registry($this->dbal);

        $this->expectException(RegistryException::class);

        $r->linkTable(new Entity(), 'default', 'table');
    }

    public function testHasTableException(): void
    {
        $r = new Registry($this->dbal);

        $this->expectException(RegistryException::class);

        $r->hasTable(new Entity());
    }

    public function testGetTableException(): void
    {
        $r = new Registry($this->dbal);

        $this->expectException(RegistryException::class);

        $r->getTable(new Entity());
    }

    public function testGetDatabaseException(): void
    {
        $r = new Registry($this->dbal);

        $this->expectException(RegistryException::class);

        $r->getDatabase(new Entity());
    }

    public function testGetTableSchemaException(): void
    {
        $r = new Registry($this->dbal);

        $this->expectException(RegistryException::class);

        $r->getTableSchema(new Entity());
    }

    public function testRegisterChildNoEntity(): void
    {
        $e = new Entity();
        $e->setRole('parent');
        $e->setClass(Author::class);

        $e->getFields()->set(
            'id',
            (new Field())->setType('primary')->setColumn('id'),
        );

        $r = new Registry($this->dbal);

        $c = new Entity();
        $c->setRole('parent');
        $c->setClass(User::class);

        $c->getFields()->set(
            'id',
            (new Field())->setType('primary')->setColumn('id'),
        );

        $c->getFields()->set(
            'name',
            (new Field())->setType('string')->setColumn('name'),
        );

        $this->expectException(RegistryException::class);

        $r->registerChild($e, $c);
    }

    public function testRegisterChild(): void
    {
        $e = new Entity();
        $e->setRole('parent');
        $e->setClass(Author::class);

        $e->getFields()->set(
            'id',
            (new Field())->setType('primary')->setColumn('id'),
        );

        $r = new Registry($this->dbal);
        $r->register($e)->linkTable($e, 'default', 'table');

        $c = new Entity();
        $c->setRole('child');
        $c->setClass(User::class);

        $c->getFields()->set(
            'id',
            (new Field())->setType('primary')->setColumn('id'),
        );

        $c->getFields()->set(
            'name',
            (new Field())->setType('string')->setColumn('name'),
        );

        $r->registerChild($e, $c);
        $this->assertTrue($e->getFields()->has('name'));

        $schema = (new Compiler())->compile($r, []);
    }

    public function testRegisterChildWithoutMerge(): void
    {
        $parent = new Entity();
        $parent->setRole('parent');
        $parent->setClass(Author::class);

        $registry = new Registry($this->dbal);
        $registry->register($parent)->linkTable($parent, 'default', 'table');

        $child = new Entity();
        $child->setRole('child');
        $child->setClass(User::class);

        $registry->registerChildWithoutMerge($parent, $child);

        $this->assertSame([$child], $registry->getChildren($parent));
    }
}
