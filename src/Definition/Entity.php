<?php

/**
 * Cycle ORM Schema Builder.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Cycle\Schema\Definition;

use Cycle\Schema\Definition\Map\FieldMap;
use Cycle\Schema\Definition\Map\OptionMap;
use Cycle\Schema\Definition\Map\RelationMap;
use Cycle\Schema\Exception\EntityException;

/**
 * Contains information about specific entity definition.
 */
final class Entity
{
    /** @var OptionMap */
    private $options;

    /** @var string */
    private $role;

    /** @var string|null */
    private $class;

    /** @var string|null */
    private $mapper;

    /** @var string|null */
    private $source;

    /** @var string|null */
    private $constrain;

    /** @var string|null */
    private $repository;

    /** @var FieldMap */
    private $fields;

    /** @var RelationMap */
    private $relations;

    /** @var array */
    private $schema = [];

    /** @var FieldMap */
    private $primaryKeyFields;

    /**
     * Entity constructor.
     */
    public function __construct()
    {
        $this->options = new OptionMap();
        $this->fields = new FieldMap();
        $this->primaryKeyFields = new FieldMap();
        $this->relations = new RelationMap();
    }

    /**
     * Full entity copy.
     */
    public function __clone()
    {
        $this->options = clone $this->options;
        $this->fields = clone $this->fields;
        $this->primaryKeyFields = clone $this->primaryKeyFields;
        $this->relations = clone $this->relations;
    }

    /**
     * @return OptionMap
     */
    public function getOptions(): OptionMap
    {
        return $this->options;
    }

    /**
     * @param string $role
     * @return Entity
     */
    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /***
     * @param string $class
     * @return Entity
     */
    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @param string|null $mapper
     * @return Entity
     */
    public function setMapper(?string $mapper): self
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * @return string
     */
    public function getMapper(): ?string
    {
        return $this->normalizeClass($this->mapper);
    }

    /**
     * @param string|null $source
     * @return Entity
     */
    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource(): ?string
    {
        return $this->normalizeClass($this->source);
    }

    /**
     * @param string|null $constrain
     * @return Entity
     */
    public function setConstrain(?string $constrain): self
    {
        $this->constrain = $constrain;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getConstrain(): ?string
    {
        return $this->normalizeClass($this->constrain);
    }

    /**
     * @param string|null $repository
     * @return Entity
     */
    public function setRepository(?string $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepository(): ?string
    {
        return $this->normalizeClass($this->repository);
    }

    /**
     * @return FieldMap
     */
    public function getFields(): FieldMap
    {
        return $this->fields;
    }

    /**
     * @return RelationMap
     */
    public function getRelations(): RelationMap
    {
        return $this->relations;
    }

    /**
     * @param array $schema
     * @return Entity
     */
    public function setSchema(array $schema): Entity
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * @return array
     */
    public function getSchema(): array
    {
        return $this->schema;
    }

    /**
     * Merge entity relations and fields.
     *
     * @param Entity $entity
     */
    public function merge(Entity $entity): void
    {
        foreach ($entity->getRelations() as $name => $relation) {
            if (!$this->relations->has($name)) {
                $this->relations->set($name, $relation);
            }
        }

        foreach ($entity->getFields() as $name => $field) {
            if (!$this->fields->has($name)) {
                $this->fields->set($name, $field);
            }
        }
    }

    /**
     * Check if entity has primary key
     */
    public function hasPrimaryKey(): bool
    {
        if ($this->primaryKeyFields->count() > 0) {
            return true;
        }

        foreach ($this->getFields() as $field) {
            if ($field->isPrimary()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set primary key columns
     * Column names will be converted into property names
     */
    public function setPrimaryKeys(array $columns): void
    {
        $this->primaryKeyFields = new FieldMap();

        foreach ($columns as $column) {
            $name = $this->getFields()->getKeyByColumnName($column);
            $this->primaryKeyFields->set($name, $this->getFields()->get($name));
        }
    }

    /**
     * Get entity primary key property names
     * @return FieldMap
     */
    public function getPrimaryFields(): FieldMap
    {
        $map = new FieldMap();

        foreach ($this->getFields() as $name => $field) {
            if ($field->isPrimary()) {
                $map->set($name, $field);
            }
        }

        if ($this->primaryKeyFields->count() > 0 && $map->count() === 0) {
            return $this->primaryKeyFields;
        }

        if ($this->primaryKeyFields->count() === 0 && $map->count() > 0) {
            return $map;
        }

        if (
            $this->primaryKeyFields->count() !== $map->count()
            || array_diff($map->getColumnNames(), $this->primaryKeyFields->getColumnNames()) != []
        ) {
            throw new EntityException("Ambiguous primary key definition for `{$this->getRole()}`.");
        }

        return $this->primaryKeyFields;
    }

    /**
     * @param string|null $class
     * @return string|null
     */
    private function normalizeClass(string $class = null): ?string
    {
        if ($class === null) {
            return null;
        }

        return ltrim($class, '\\');
    }
}
