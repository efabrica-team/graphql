<?php

namespace Efabrica\GraphQL\Schema\Definition\Types;

use Efabrica\GraphQL\Schema\Definition\HasSettigns;

abstract class Type
{
    use HasSettigns;

    protected string $name;

    protected ?string $description = null;

    public function __construct(string $name)
    {
        $this->setName($name);
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
}
