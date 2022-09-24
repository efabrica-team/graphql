<?php

namespace Efabrica\GraphQL\Schema\Definition;

use Efabrica\GraphQL\Schema\Definition\Fields\Field;

final class ResolveInfo
{
    private Field $field;

    private array $path;

    private array $fieldSelection;

    public function __construct(Field $field, array $path, array $fieldSelection)
    {
        $this->setField($field);
        $this->setPath($path);
        $this->setFieldSelection($fieldSelection);
    }

    public function getField(): Field
    {
        return $this->field;
    }

    public function setField(Field $field): self
    {
        $this->field = $field;
        return $this;
    }

    public function getPath(): array
    {
        return $this->path;
    }

    public function setPath(array $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getFieldSelection(): array
    {
        return $this->fieldSelection;
    }

    public function setFieldSelection(array $fieldSelection): self
    {
        $this->fieldSelection = $fieldSelection;
        return $this;
    }
}
