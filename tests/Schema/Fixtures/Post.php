<?php

/**
 * Cycle ORM Schema Builder.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Cycle\Schema\Tests\Fixtures;

use Cycle\Schema\Definition\Entity;
use Cycle\Schema\Definition\Field;
use Cycle\Schema\Definition\Relation;

class Post implements ParentInterface
{
    public static function define(): Entity
    {
        $entity = new Entity();
        $entity->setRole('post');
        $entity->setClass(self::class);

        $entity->getFields()->set(
            'id',
            (new Field())->setType('primary')->setColumn('id')->setPrimary(true)
        );

        $entity->getRelations()->set(
            'author',
            (new Relation())->setTarget(Author::class)->setType('belongsTo')
        );

        return $entity;
    }

    public static function defineCompositePK(): Entity
    {
        $entity = self::define();

        $entity->getFields()->set(
            'slug',
            (new Field())->setType('primary')->setColumn('slug')->setPrimary(true)
        );

        return $entity;
    }
}
