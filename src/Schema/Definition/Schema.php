<?php

namespace Efabrica\GraphQL\Schema\Definition;

use Efabrica\GraphQL\Schema\Definition\Types\ObjectType;

class Schema
{
    private ?ObjectType $query = null;

    public function getQuery(): ?ObjectType
    {
        return $this->query;
    }

    public function setQuery(?ObjectType $query): self
    {
        $this->query = $query;
        return $this;
    }
}
