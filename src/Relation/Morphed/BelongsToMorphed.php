<?php

/**
 * Cycle ORM Schema Builder.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Cycle\Schema\Relation\Morphed;

use Cycle\ORM\Relation;
use Cycle\Schema\Exception\RelationException;
use Cycle\Schema\InversableInterface;
use Cycle\Schema\Registry;
use Cycle\Schema\Relation\RelationSchema;
use Cycle\Schema\Relation\Traits\FieldTrait;
use Cycle\Schema\Relation\Traits\MorphTrait;
use Cycle\Schema\RelationInterface;

final class BelongsToMorphed extends RelationSchema implements InversableInterface
{
    use FieldTrait;
    use MorphTrait;

    // internal relation type
    protected const RELATION_TYPE = Relation::BELONGS_TO_MORPHED;

    // relation schema options
    protected const RELATION_SCHEMA = [
        // save with parent
        Relation::CASCADE                => true,

        // do not pre-load relation by default
        Relation::LOAD                   => Relation::LOAD_PROMISE,

        // nullable by default
        Relation::NULLABLE               => true,

        // default field name for inner key
        Relation::OUTER_KEY              => '{target:primaryKey}',

        // link to parent entity primary key by default
        Relation::INNER_KEY              => '{relation}_{outerKey}',

        // link to parent entity primary key by default
        Relation::MORPH_KEY              => '{relation}_role',

        // rendering options
        RelationSchema::INDEX_CREATE     => true,
        RelationSchema::MORPH_KEY_LENGTH => 32
    ];

    /**
     * @param Registry $registry
     */
    public function compute(Registry $registry): void
    {
        // compute local key
        $this->options = $this->options->withContext([
            'source:primaryKey' => $registry->getEntity($this->source)->getPrimaryKeys()
        ]);

        $source = $registry->getEntity($this->source);

        list($outerKeys, $outerFields) = $this->findOuterKey($registry, $this->target);

        // register primary key reference
        $this->options = $this->options->withContext([
            'target:primaryKey' => $outerKeys
        ]);

        // create target outer field
        foreach ((array)$this->options->get(Relation::INNER_KEY) as $i => $key) {
            $this->ensureField(
                $source,
                $key,
                $outerFields[$i],
                $this->options->get(Relation::NULLABLE)
            );
        }

        foreach ((array)$this->options->get(Relation::MORPH_KEY) as $key) {
            $this->ensureMorphField(
                $source,
                $key,
                $this->options->get(RelationSchema::MORPH_KEY_LENGTH),
                $this->options->get(Relation::NULLABLE)
            );
        }
    }

    /**
     * @param Registry $registry
     */
    public function render(Registry $registry): void
    {
        $source = $registry->getEntity($this->source);

        $innerFields = $this->getFields($source, Relation::INNER_KEY);
        $morphFields = $this->getFields($source, Relation::MORPH_KEY);

        $table = $registry->getTableSchema($source);

        if ($this->options->get(self::INDEX_CREATE)) {
            $index = array_merge($innerFields->getColumnNames(), $morphFields->getColumnNames());
            if (count($index) > 0) {
                $table->index($index);
            }
        }
    }

    /**
     * @param Registry $registry
     * @return array
     */
    public function inverseTargets(Registry $registry): array
    {
        return iterator_to_array($this->findTargets($registry, $this->target));
    }

    /**
     * @param RelationInterface $relation
     * @param string            $into
     * @param int|null          $load
     * @return RelationInterface
     *
     * @throws RelationException
     */
    public function inverseRelation(RelationInterface $relation, string $into, ?int $load = null): RelationInterface
    {
        if (!$relation instanceof MorphedHasOne && !$relation instanceof MorphedHasMany) {
            throw new RelationException(
                'BelongsToMorphed relation can only be inversed into MorphedHasOne or MorphedHasMany'
            );
        }

        return $relation->withContext(
            $into,
            $this->target,
            $this->source,
            $this->options->withOptions([
                Relation::LOAD      => $load,
                Relation::INNER_KEY => $this->options->get(Relation::OUTER_KEY),
                Relation::OUTER_KEY => $this->options->get(Relation::INNER_KEY),
            ])
        );
    }
}
