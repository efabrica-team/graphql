<?php

namespace Efabrica\GraphQL\Schema\Definition\Types;

class UnionType extends Type
{
    /**
     * @var ObjectType[]
     */
    protected array $objectTypes = [];

    /** @var callable */
    protected $objectTypesCallback;

    /** @var callable */
    protected $resolveType;

    public function getObjectTypes(): array
    {
        if ($this->objectTypesCallback === null) {
            return $this->objectTypes;
        }

        $objectTypes = [];
        foreach (call_user_func($this->objectTypesCallback) as $type) {
            $objectTypes[$type->getName()] = $type;
        }

        return array_merge($objectTypes, $this->objectTypes);
    }

    public function addObjectType(ObjectType $objectType): self
    {
        $this->objectTypes[$objectType->getName()] = $objectType;
        return $this;
    }

    /**
     * @param ObjectType[]|callable $objectTypes
     */
    public function setObjectTypes($objectTypes): self
    {
        if (is_callable($objectTypes)) {
            $this->objectTypesCallback = $objectTypes;
        } else {
            $this->objectTypes = [];
            foreach ($objectTypes as $objectType) {
                $this->objectTypes[$objectType->getName()] = $objectType;
            }
        }

        return $this;
    }

    public function getResolveType(): callable
    {
        return $this->resolveType;
    }

    public function setResolveType(callable $resolveType): self
    {
        $this->resolveType = $resolveType;
        return $this;
    }
}
