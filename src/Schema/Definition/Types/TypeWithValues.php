<?php

namespace Efabrica\GraphQL\Schema\Definition\Types;

use Efabrica\GraphQL\Schema\Definition\Values\Value;

abstract class TypeWithValues extends Type
{
    /** @var Value[] */
    protected array $values = [];

    public function getValues(): array
    {
        return $this->values;
    }

    public function addValue(Value $value): self
    {
        $this->values[] = $value;
        return $this;
    }

    public function setValues(array $values): self
    {
        $this->values = $values;
        return $this;
    }
}
