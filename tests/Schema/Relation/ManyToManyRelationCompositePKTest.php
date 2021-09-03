<?php

/**
 * Cycle ORM Schema Builder.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Cycle\Schema\Tests\Relation;

use Cycle\ORM\Relation;
use Cycle\ORM\Schema;
use Cycle\Schema\Compiler;
use Cycle\Schema\Definition\Relation as RelationDefinition;
use Cycle\Schema\Exception\SchemaException;
use Cycle\Schema\Generator\GenerateRelations;
use Cycle\Schema\Generator\RenderRelations;
use Cycle\Schema\Generator\RenderTables;
use Cycle\Schema\Registry;
use Cycle\Schema\Relation\BelongsTo;
use Cycle\Schema\Relation\ManyToMany;
use Cycle\Schema\Tests\BaseTest;
use Cycle\Schema\Tests\Fixtures\Post;
use Cycle\Schema\Tests\Fixtures\Tag;
use Cycle\Schema\Tests\Fixtures\TagContext;

abstract class ManyToManyRelationCompositePKTest extends BaseTest
{
    public function testGenerate(): void
    {
        $post = Post::defineCompositePK();
        $tag = Tag::defineCompositePK();
        $tagContext = TagContext::define();

        $post->getRelations()->remove('author');

        $post->getRelations()->set('tags', new RelationDefinition());
        $post->getRelations()->get('tags')
            ->setType('manyToMany')
            ->setTarget('tag')
            ->getOptions()->set('though', 'tagContext');

        $r = new Registry($this->dbal);
        $r->register($post)->linkTable($post, 'default', 'post');
        $r->register($tag)->linkTable($tag, 'default', 'tag');
        $r->register($tagContext)->linkTable($tagContext, 'default', 'tag_context');

        (new GenerateRelations(['manyToMany' => new ManyToMany()]))->run($r);

        $this->assertInstanceOf(ManyToMany::class, $r->getRelation($post, 'tags'));
    }

    public function testDifferentDatabases(): void
    {
        $post = Post::defineCompositePK();
        $tag = Tag::defineCompositePK();
        $tagContext = TagContext::define();

        $post->getRelations()->remove('author');

        $post->getRelations()->set('tags', new RelationDefinition());
        $post->getRelations()->get('tags')
            ->setType('manyToMany')
            ->setTarget('tag')
            ->getOptions()->set('though', 'tagContext');

        $r = new Registry($this->dbal);
        $r->register($post)->linkTable($post, 'default', 'post');
        $r->register($tag)->linkTable($tag, 'secondary', 'tag');
        $r->register($tagContext)->linkTable($tagContext, 'default', 'tag_context');

        $this->expectException(SchemaException::class);

        (new GenerateRelations(['manyToMany' => new ManyToMany()]))->run($r);
    }

    public function testDifferentDatabases2(): void
    {
        $post = Post::defineCompositePK();
        $tag = Tag::defineCompositePK();
        $tagContext = TagContext::define();

        $post->getRelations()->remove('author');

        $post->getRelations()->set('tags', new RelationDefinition());
        $post->getRelations()->get('tags')
            ->setType('manyToMany')
            ->setTarget('tag')
            ->getOptions()->set('though', 'tagContext');

        $r = new Registry($this->dbal);
        $r->register($post)->linkTable($post, 'default', 'post');
        $r->register($tag)->linkTable($tag, 'default', 'tag');
        $r->register($tagContext)->linkTable($tagContext, 'secondary', 'tag_context');

        $this->expectException(SchemaException::class);

        (new GenerateRelations(['manyToMany' => new ManyToMany()]))->run($r);
    }

    public function testPackSchema(): void
    {
        $post = Post::defineCompositePK();
        $tag = Tag::defineCompositePK();
        $tagContext = TagContext::define();

        $post->getRelations()->remove('author');

        $post->getRelations()->set('tags', new RelationDefinition());
        $post->getRelations()->get('tags')
            ->setType('manyToMany')
            ->setTarget('tag')
            ->getOptions()->set('though', 'tagContext');

        $r = new Registry($this->dbal);
        $r->register($post)->linkTable($post, 'default', 'post');
        $r->register($tag)->linkTable($tag, 'default', 'tag');
        $r->register($tagContext)->linkTable($tagContext, 'default', 'tag_context');

        (new GenerateRelations(['manyToMany' => new ManyToMany()]))->run($r);
        $schema = (new Compiler())->compile($r);

        $this->assertArrayHasKey('post', $schema);
        $this->assertArrayHasKey('tag', $schema);
        $this->assertArrayHasKey('tagContext', $schema);

        $this->assertArrayHasKey('tags', $schema['post'][Schema::RELATIONS]);

        $this->assertSame('tag', $schema['post'][Schema::RELATIONS]['tags'][Relation::TARGET]);
        $this->assertSame(
            Relation::MANY_TO_MANY,
            $schema['post'][Schema::RELATIONS]['tags'][Relation::TYPE]
        );

        $this->assertSame(
            ['id', 'slug'],
            $schema['post'][Schema::RELATIONS]['tags'][Relation::SCHEMA][Relation::INNER_KEY]
        );

        $this->assertSame(
            ['id', 'slug'],
            $schema['post'][Schema::RELATIONS]['tags'][Relation::SCHEMA][Relation::OUTER_KEY]
        );

        $this->assertSame(
            ['post_id', 'post_slug'],
            $schema['post'][Schema::RELATIONS]['tags'][Relation::SCHEMA][Relation::THROUGH_INNER_KEY]
        );

        $this->assertSame(
            ['tag_id', 'tag_slug'],
            $schema['post'][Schema::RELATIONS]['tags'][Relation::SCHEMA][Relation::THROUGH_OUTER_KEY]
        );

        $this->assertSame(
            'tagContext',
            $schema['post'][Schema::RELATIONS]['tags'][Relation::SCHEMA][Relation::THROUGH_ENTITY]
        );
    }

    public function testRenderTables(): void
    {
        $post = Post::defineCompositePK();
        $tag = Tag::defineCompositePK();
        $tagContext = TagContext::define();

        $post->getRelations()->remove('author');

        $post->getRelations()->set('tags', new RelationDefinition());
        $post->getRelations()->get('tags')
            ->setType('manyToMany')
            ->setTarget('tag')
            ->getOptions()->set('though', 'tagContext');

        $r = new Registry($this->dbal);
        $r->register($post)->linkTable($post, 'default', 'post');
        $r->register($tag)->linkTable($tag, 'default', 'tag');
        $r->register($tagContext)->linkTable($tagContext, 'default', 'tag_context');

        (new Compiler())->compile($r, [
            (new GenerateRelations(['manyToMany' => new ManyToMany()])),
            $t = new RenderTables(),
            new RenderRelations(),
        ]);

        $t->getReflector()->run();

        $table = $this->getDriver()->getSchema('tag_context');

        $this->assertTrue($table->hasColumn('post_id'));
        $this->assertTrue($table->hasColumn('tag_id'));
        $this->assertTrue($table->hasIndex(['post_id', 'post_slug', 'tag_id', 'tag_slug']));
        $this->assertTrue($table->hasForeignKey(['post_id', 'post_slug']));
        $this->assertTrue($table->hasForeignKey(['tag_id', 'tag_slug']));
    }

    public function testInverseInvalidType(): void
    {
        $post = Post::defineCompositePK();
        $tag = Tag::defineCompositePK();
        $tagContext = TagContext::define();

        $post->getRelations()->remove('author');

        $post->getRelations()->set('tags', new RelationDefinition());
        $post->getRelations()->get('tags')
            ->setType('manyToMany')
            ->setTarget('tag')
            ->setInverse('posts', 'belongsTo')
            ->getOptions()->set('though', 'tagContext');

        $r = new Registry($this->dbal);
        $r->register($post)->linkTable($post, 'default', 'post');
        $r->register($tag)->linkTable($tag, 'default', 'tag');
        $r->register($tagContext)->linkTable($tagContext, 'default', 'tag_context');

        $this->expectException(SchemaException::class);

        (new GenerateRelations([
            'manyToMany' => new ManyToMany(),
            'belongsTo' => new BelongsTo(),
        ]))->run($r);
    }

    public function testInverse(): void
    {
        $post = Post::defineCompositePK();
        $tag = Tag::defineCompositePK();
        $tagContext = TagContext::define();

        $post->getRelations()->remove('author');

        $post->getRelations()->set('tags', new RelationDefinition());
        $post->getRelations()->get('tags')
            ->setType('manyToMany')
            ->setTarget('tag')
            ->setInverse('posts', 'manyToMany')
            ->getOptions()->set('though', 'tagContext');

        $r = new Registry($this->dbal);
        $r->register($post)->linkTable($post, 'default', 'post');
        $r->register($tag)->linkTable($tag, 'default', 'tag');
        $r->register($tagContext)->linkTable($tagContext, 'default', 'tag_context');

        (new GenerateRelations([
            'manyToMany' => new ManyToMany(),
        ]))->run($r);

        $schema = (new Compiler())->compile($r);

        $this->assertArrayHasKey('post', $schema);
        $this->assertArrayHasKey('tag', $schema);
        $this->assertArrayHasKey('tagContext', $schema);

        $this->assertArrayHasKey('posts', $schema['tag'][Schema::RELATIONS]);

        $this->assertSame('post', $schema['tag'][Schema::RELATIONS]['posts'][Relation::TARGET]);
        $this->assertSame(
            Relation::MANY_TO_MANY,
            $schema['tag'][Schema::RELATIONS]['posts'][Relation::TYPE]
        );

        $this->assertSame(
            ['id', 'slug'],
            $schema['tag'][Schema::RELATIONS]['posts'][Relation::SCHEMA][Relation::INNER_KEY]
        );

        $this->assertSame(
            ['id', 'slug'],
            $schema['tag'][Schema::RELATIONS]['posts'][Relation::SCHEMA][Relation::OUTER_KEY]
        );

        $this->assertSame(
            ['tag_id', 'tag_slug'],
            $schema['tag'][Schema::RELATIONS]['posts'][Relation::SCHEMA][Relation::THROUGH_INNER_KEY]
        );

        $this->assertSame(
            ['post_id', 'post_slug'],
            $schema['tag'][Schema::RELATIONS]['posts'][Relation::SCHEMA][Relation::THROUGH_OUTER_KEY]
        );

        $this->assertSame(
            'tagContext',
            $schema['tag'][Schema::RELATIONS]['posts'][Relation::SCHEMA][Relation::THROUGH_ENTITY]
        );
    }
}
