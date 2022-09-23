<?php

namespace Efabrica\GraphQL\Schema\Definition\Arguments;

use Efabrica\GraphQL\Schema\Definition\HasSettigns;
use Efabrica\GraphQL\Schema\Definition\Types\Type;
use Error;

class FieldArgument
{
    use HasSettigns;

    protected Type $type;

    protected string $name;

    protected ?string $description = null;

    /** @var mixed */
    protected $defaultValue;

    protected bool $isDefaultValueSet = false;

    protected bool $multi = false;

    public function __construct(string $name, Type $type)
    {
        $this->setName($name);
        $this->setType($type);
    }

    public function getType(): Type
    {
        return $this->type;
    }

    /* @return static */
    public function setType(Type $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /* @return static */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /* @return static */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDefaultValue()
    {
        if (!$this->isDefaultValueSet) {
            throw new Error('Property $defaultValue must not be accessed before initialization');
        }
        return $this->defaultValue;
    }

    /* @return static */
    public function setDefaultValue($defaultValue): self
    {
        $this->defaultValue = $defaultValue;
        $this->isDefaultValueSet = true;
        return $this;
    }

    public function isMulti(): bool
    {
        return $this->multi;
    }

    /* @return static */
    public function setMulti(bool $multi = true): self
    {
        $this->multi = $multi;
        return $this;
    }
}
