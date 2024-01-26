<?php

namespace Efabrica\GraphQL\Schema\Transformers;

use Efabrica\GraphQL\Exceptions\SchemaTransformerException;
use Efabrica\GraphQL\Schema\Definition\Arguments\FieldArgument;
use Efabrica\GraphQL\Schema\Definition\Fields\Field;
use Efabrica\GraphQL\Schema\Definition\Fields\InputObjectField;
use Efabrica\GraphQL\Schema\Definition\ResolveInfo;
use Efabrica\GraphQL\Schema\Definition\Schema;
use Efabrica\GraphQL\Schema\Definition\Types\EnumType;
use Efabrica\GraphQL\Schema\Definition\Types\InputObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\ObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\BooleanType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\FloatType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\IDType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\IntType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\ScalarType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\StringType;
use Efabrica\GraphQL\Schema\Definition\Types\Type;
use Efabrica\GraphQL\Schema\Definition\Types\UnionType;
use GraphQL\Type\Definition\EnumType as WebonyxEnumType;
use GraphQL\Type\Definition\InputObjectType as WebonyxInputObjectType;
use GraphQL\Type\Definition\ObjectType as WebonyxObjectType;
use GraphQL\Type\Definition\ResolveInfo as WebonyxResolveInfo;
use GraphQL\Type\Definition\Type as WebonyxType;
use GraphQL\Type\Definition\UnionType as WebonyxUnionType;
use GraphQL\Type\Schema as WebonyxSchema;
use GraphQL\Type\SchemaConfig as WebonyxSchemaConfig;
use Throwable;

final class WebonyxSchemaTransformer
{
    /**
     * @var WebonyxType[]
     */
    private array $types = [];

    public function handle(Schema $schema): WebonyxSchema
    {
        $schemaConfig = new WebonyxSchemaConfig();

        /** @var WebonyxObjectType $query */
        $query = $this->transformType($schema->getQuery());
        $schemaConfig->setQuery($query);
        $this->types = [];

        return new WebonyxSchema($schemaConfig);
    }

    private function transformType(Type $type): WebonyxType
    {
        if (isset($this->types[$type->getName()])) {
            return $this->types[$type->getName()];
        }

        $transformedType = null;
        if ($type instanceof ScalarType) {
            $scalarTypeMap = [
                BooleanType::class => WebonyxType::boolean(),
                FloatType::class => WebonyxType::float(),
                IntType::class => WebonyxType::int(),
                StringType::class => WebonyxType::string(),
                IDType::class => WebonyxType::id(),
            ];

            //TODO: build custom scalar type
            $transformedType = $scalarTypeMap[get_class($type)] ?? WebonyxType::string();
        } elseif ($type instanceof ObjectType) {
            $transformedType = new WebonyxObjectType([
                'name' => $type->getName(),
                'description' => $type->getDescription(),
                'fields' => function () use ($type) {
                    $fields = [];
                    foreach ($type->getFields() as $field) {
                        $fields[] = $this->transformField($field);
                    }
                    return $fields;
                },
            ]);
        } elseif ($type instanceof EnumType) {
            $values = [];
            foreach ($type->getValues() as $value) {
                $values[$value->getName()] = [
                    'value' => $value->getValue(),
                    'description' => $value->getDescription(),
                ];
            }
            $transformedType = new WebonyxEnumType([
                'name' => $type->getName(),
                'description' => $type->getDescription(),
                'values' => $values,
            ]);
        } elseif ($type instanceof UnionType) {
            $transformedType = new WebonyxUnionType([
                'name' => $type->getName(),
                'description' => $type->getDescription(),
                'types' => function () use ($type) {
                    $objectTypes = [];
                    foreach ($type->getObjectTypes() as $objectType) {
                        $objectTypes[] = $this->transformType($objectType);
                    }
                    return $objectTypes;
                },
                'resolveType' => function($value) use ($type): WebonyxObjectType {
                    $objectType = $this->transformType(call_user_func($type->getResolveType(), $value));
                    if (!$objectType instanceof WebonyxObjectType) {
                        throw new SchemaTransformerException('Union resolveType must be instance of "' . WebonyxObjectType::class . '".');
                    }
                    return $objectType;
                },
            ]);
        } elseif ($type instanceof InputObjectType) {
            $transformedType = new WebonyxInputObjectType([
                'name' => $type->getName(),
                'description' => $type->getDescription(),
                'fields' => function () use ($type) {
                    $fields = [];
                    foreach ($type->getFields() as $field) {
                        $fields[] = $this->transformField($field);
                    }
                    return $fields;
                },
            ]);
        }

        if (!$transformedType) {
            throw new SchemaTransformerException(
                'Type of \'' . get_class($type) . '\' could not be transformed to webonyx type.'
            );
        }

        $transformedType->config['original_type'] = $type;

        $this->types[$type->getName()] = $transformedType;

        return $transformedType;
    }

    private function transformField(Field $field): array
    {
        $fieldType = $this->transformType($field->getType());
        if ($field->isMulti()) {
            $fieldType = $field->isMultiItemNullable() ? $fieldType : WebonyxType::nonNull($fieldType);
            $fieldType = WebonyxType::listOf($fieldType);
        }
        if (!$field->isNullable()) {
            $fieldType = WebonyxType::nonNull($fieldType);
        }

        $fieldArguments = [];
        foreach ($field->getArguments() as $argument) {
            $fieldArguments[] = $this->transformFieldArgument($argument);
        }

        $fieldResult = [
            'name' => $field->getName(),
            'description' => $field->getDescription(),
            'type' => $fieldType,
            'args' => $fieldArguments,
            'original_field' => $field,
        ];

        if ($field instanceof InputObjectField) {
            try {
                $fieldResult['defaultValue'] = $field->getDefaultValue();
            } catch (Throwable $e) {
                //
            }
        }

        if ($resolver = $field->getResolver()) {
            $fieldResult['resolve'] = function (
                $rootValue,
                array $args,
                $context,
                WebonyxResolveInfo $resolveInfo
            ) use ($resolver) {
                return $resolver($rootValue, $args, $this->transformResolveInfo($resolveInfo));
            };
        }

        return $fieldResult;
    }

    private function transformFieldArgument(FieldArgument $fieldArgument): array
    {
        $argumentType = $this->transformType($fieldArgument->getType());
        if ($fieldArgument->isMulti()) {
            $argumentType = WebonyxType::listOf($argumentType);
        }

        $argumentResult = [
            'name' => $fieldArgument->getName(),
            'type' => $argumentType,
            'description' => $fieldArgument->getDescription(),
        ];

        try {
            $argumentResult['defaultValue'] = $fieldArgument->getDefaultValue();
        } catch (Throwable $e) {
            //
        }

        return $argumentResult;
    }

    private function transformResolveInfo(WebonyxResolveInfo $webonyxResolveInfo): ResolveInfo
    {
        /** @var array $fieldDefinitionConfig */
        $fieldDefinitionConfig = $webonyxResolveInfo->fieldDefinition->config;
        return new ResolveInfo(
            $fieldDefinitionConfig['original_field'],
            $webonyxResolveInfo->path,
            $webonyxResolveInfo->getFieldSelection(),
        );
    }
}
