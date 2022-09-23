<?php

namespace Efabrica\GraphQL\Schema\Definition\Values;

class Value
{
    protected string $name;

    /** @var mixed */
    protected $value;

    protected ?string $description = null;

    public function __construct(string $name, $value)
    {
        $this->setName($name);
        $this->setValue($value);
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

    public function getValue()
    {
        return $this->value;
    }

    /* @return static */
    public function setValue(?string $value): self
    {
        $this->value = $value;
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
}
