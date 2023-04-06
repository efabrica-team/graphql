<?php

namespace Tests\Unit\Schema\Loaders;

use Efabrica\GraphQL\Schema\Definition\Arguments\FieldArgument;
use Efabrica\GraphQL\Schema\Definition\Fields\Field;
use Efabrica\GraphQL\Schema\Definition\Fields\InputObjectField;
use Efabrica\GraphQL\Schema\Definition\Schema;
use Efabrica\GraphQL\Schema\Definition\Types\InputObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\ObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\BooleanType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\IntType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\StringType;
use Efabrica\GraphQL\Schema\Loaders\DefinitionSchemaLoader;
use Efabrica\GraphQL\Schema\Loaders\MultiSchemaLoader;
use Efabrica\GraphQL\Schema\Transformers\WebonyxSchemaTransformer;
use GraphQL\Utils\SchemaPrinter;
use PHPUnit\Framework\TestCase;

class MultiSchemaLoaderTest extends TestCase
{
    private WebonyxSchemaTransformer $schemaTransformer;

    private DefinitionSchemaLoader $originalSchemaLoader;

    protected function setUp(): void
    {
        $this->schemaTransformer = new WebonyxSchemaTransformer();
        $this->originalSchemaLoader = new DefinitionSchemaLoader(
            (new Schema())
                ->setQuery(
                    (new ObjectType('Query'))
                        ->addField(
                            (new Field(
                                'Users',
                                (new ObjectType('User'))
                                    ->addField(new Field('id', new IntType()))
                                    ->addField(new Field('name', new StringType()))
                                    ->addField(new Field('email', new StringType()))
                                    ->addField(
                                        (new Field(
                                            'role',
                                            (new ObjectType('Role'))
                                                ->addField(new Field('id', new IntType()))
                                                ->addField(new Field('name', new StringType()))
                                        ))
                                    )
                                    ->addField(
                                        (new Field('created_at', new StringType()))
                                            ->setNullable()
                                    )
                                    ->addField(
                                        (new Field('updated_at', new StringType()))
                                            ->setNullable()
                                    )
                            ))
                                ->setMulti()
                                ->addArgument(
                                    new FieldArgument(
                                        'Pagination',
                                        (new InputObjectType('Pagination'))
                                            ->addField(
                                                (new InputObjectField('limit', new IntType()))
                                                    ->setNullable()
                                            )
                                            ->addField(
                                                (new InputObjectField('offset', new IntType()))
                                                    ->setNullable()
                                            )
                                    )
                                )
                        )
                )
        );
    }

    public function testCanMergeSchemas(): void
    {
        $overrideSchemaLoader = new DefinitionSchemaLoader(
            (new Schema())
                ->setQuery(
                    (new ObjectType('Query'))
                        ->addField(
                            (new Field(
                                'Users',
                                (new ObjectType('User'))
                                    ->addField(
                                        (new Field('name', new StringType()))
                                            ->setNullable()
                                    )
                                    ->addField(new Field('date_of_birth', new StringType()))
                            ))
                                ->setMulti()
                                ->addArgument(
                                    new FieldArgument(
                                        'Pagination',
                                        (new InputObjectType('Pagination'))
                                            ->addField(
                                                (new InputObjectField('limit', new IntType()))
                                            )
                                            ->addField(new InputObjectField('max', new IntType()))
                                    )
                                )
                                ->addArgument(new FieldArgument('show_only_admins', new BooleanType()))
                        )
                )
        );

        $expectedSchema = (new Schema())
            ->setQuery(
                (new ObjectType('Query'))
                    ->addField(
                        (new Field(
                            'Users',
                            (new ObjectType('User'))
                                ->addField(new Field('id', new IntType()))
                                ->addField((new Field('name', new StringType()))->setNullable())
                                ->addField(new Field('email', new StringType()))
                                ->addField(
                                    (new Field(
                                        'role',
                                        (new ObjectType('Role'))
                                            ->addField(new Field('id', new IntType()))
                                            ->addField(new Field('name', new StringType()))
                                    ))
                                )
                                ->addField(
                                    (new Field('created_at', new StringType()))
                                        ->setNullable()
                                )
                                ->addField(
                                    (new Field('updated_at', new StringType()))
                                        ->setNullable()
                                )
                                ->addField(new Field('date_of_birth', new StringType()))
                        ))
                            ->setMulti()
                            ->addArgument(
                                new FieldArgument(
                                    'Pagination',
                                    (new InputObjectType('Pagination'))
                                        ->addField(
                                            (new InputObjectField('limit', new IntType()))
                                        )
                                        ->addField(
                                            (new InputObjectField('offset', new IntType()))
                                                ->setNullable()
                                        )
                                        ->addField(new InputObjectField('max', new IntType()))
                                )
                            )
                            ->addArgument(new FieldArgument('show_only_admins', new BooleanType()))
                    )
            );

        $this->assertEquals(
            SchemaPrinter::doPrint($this->schemaTransformer->handle($expectedSchema)),
            SchemaPrinter::doPrint($this->schemaTransformer->handle((new MultiSchemaLoader($this->originalSchemaLoader, $overrideSchemaLoader))->getSchema()))
        );
    }
}
