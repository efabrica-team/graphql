<?php

namespace Efabrica\GraphQL\Schema\Definition\Fields;

use Error;

class InputObjectField extends Field
{
    /** @var mixed */
    protected $defaultValue;

    protected bool $isDefaultValueSet = false;

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
}
