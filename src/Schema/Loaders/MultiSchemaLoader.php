<?php

namespace Efabrica\GraphQL\Schema\Loaders;

use Efabrica\GraphQL\Schema\Definition\Arguments\FieldArgument;
use Efabrica\GraphQL\Schema\Definition\Fields\Field;
use Efabrica\GraphQL\Schema\Definition\Schema;
use Efabrica\GraphQL\Schema\Definition\Types\InputObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\ObjectType;
use Error;

final class MultiSchemaLoader implements SchemaLoaderInterface
{
    private array $schemaLoaders;

    public function __construct(SchemaLoaderInterface ...$schemaLoaders)
    {
        $this->schemaLoaders = $schemaLoaders;
    }

    public function getSchema(): Schema
    {
        $schema = new Schema();

        foreach ($this->schemaLoaders as $schemaLoader) {
            $schema = $this->mergeSchemas($schema, $schemaLoader->getSchema());
        }

        return $schema;
    }

    private function mergeSchemas(Schema $a, Schema $b): Schema
    {
        $a->setQuery($this->mergeObjectTypes($a->getQuery(), $b->getQuery()));

        return $a;
    }

    private function mergeObjectTypes(?ObjectType $a, ?ObjectType $b): ?ObjectType
    {
        if (!$a && !$b) {
            return null;
        }

        if ($a && !$b) {
            return $a;
        }

        if (!$a) {
            return $b;
        }

        $fields = $a->getFields();
        foreach ($b->getFields() as $bField) {
            $aField = $fields[$bField->getName()] ?? null;
            if (!$aField) {
                $fields[$bField->getName()] = $bField;
                continue;
            }
            $fields[$aField->getName()] = $this->mergeFields($aField, $bField);
        }

        if (!$b->getDescription()) {
            $b->setDescription($a->getDescription());
        }
        $b->setFields($fields);
        $b->setSettings(array_merge($a->getSettings(), $b->getSettings()));

        return $b;
    }

    private function mergeFields(Field $a, Field $b): Field
    {
        if ($a->getType() instanceof ObjectType && $b->getType() instanceof ObjectType) {
            $b->setType($this->mergeObjectTypes($a->getType(), $b->getType()));
        }

        if (!$b->getDescription()) {
            $b->setDescription($a->getDescription());
        }

        $arguments = $a->getArguments();
        foreach ($b->getArguments() as $bArgument) {
            $aArgument = $arguments[$bArgument->getName()] ?? null;
            if (!$aArgument) {
                $arguments[$bArgument->getName()] = $bArgument;
                continue;
            }
            $arguments[$aArgument->getName()] = $this->mergeFieldArguments($aArgument, $bArgument);
        }
        $b->setArguments($arguments);

        if (!$b->getResolver()) {
            $b->setResolver($a->getResolver());
        }

        $b->setSettings(array_merge($a->getSettings(), $b->getSettings()));

        return $b;
    }

    private function mergeFieldArguments(FieldArgument $a, FieldArgument $b): FieldArgument
    {
        if ($a->getType() instanceof InputObjectType && $b->getType() instanceof InputObjectType) {
            $b->setType($this->mergeInputObjectTypes($a->getType(), $b->getType()));
        }

        if (!$b->getDescription()) {
            $b->setDescription($a->getDescription());
        }

        try {
            $b->getDefaultValue();
        } catch (Error $error) {
            try {
                $b->setDefaultValue($a->getDefaultValue());
            } catch (Error $error) {
            }
        }

        return $b;
    }

    private function mergeInputObjectTypes(InputObjectType $a, InputObjectType $b): InputObjectType
    {
        $fields = $a->getFields();
        foreach ($b->getFields() as $bField) {
            $aField = $fields[$bField->getName()] ?? null;
            if (!$aField) {
                $fields[$bField->getName()] = $bField;
                continue;
            }
            $fields[$aField->getName()] = $this->mergeFields($aField, $bField);
        }

        if (!$b->getDescription()) {
            $b->setDescription($a->getDescription());
        }
        $b->setFields($fields);
        $b->setSettings(array_merge($a->getSettings(), $b->getSettings()));

        return $b;
    }
}
