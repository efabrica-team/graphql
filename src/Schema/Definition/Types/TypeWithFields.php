<?php

namespace Efabrica\GraphQL\Schema\Definition\Types;

use Efabrica\GraphQL\Schema\Definition\Fields\Field;

abstract class TypeWithFields extends Type
{
    /**
     * @var Field[]
     */
    protected array $fields = [];

    /** @var callable */
    protected $fieldsCallback;

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        if ($this->fieldsCallback === null || count($this->fields)) {
            return $this->fields;
        }

        $this->fields = [];
        foreach (call_user_func($this->fieldsCallback) as $field) {
            $this->fields[$field->getName()] = $field;
        }

        return $this->fields;
    }

    public function addField(Field $field): self
    {
        $this->fields[$field->getName()] = $field;
        return $this;
    }

    /**
     * @param array|callable $fields
     */
    public function setFields($fields): self
    {
        if (is_callable($fields)) {
            $this->fieldsCallback = $fields;
        } else {
            $this->fields = [];
            foreach ($fields as $field) {
                $this->fields[$field->getName()] = $field;
            }
        }

        return $this;
    }
}
