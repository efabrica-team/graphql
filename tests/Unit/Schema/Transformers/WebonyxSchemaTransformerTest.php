<?php

namespace Tests\Unit\Schema\Transformers;

use Efabrica\GraphQL\Exceptions\SchemaTransformerException;
use Efabrica\GraphQL\Resolvers\ResolverInterface;
use Efabrica\GraphQL\Schema\Definition\Arguments\FieldArgument;
use Efabrica\GraphQL\Schema\Definition\Fields\Field;
use Efabrica\GraphQL\Schema\Definition\ResolveInfo;
use Efabrica\GraphQL\Schema\Definition\Schema;
use Efabrica\GraphQL\Schema\Definition\Types\EnumType;
use Efabrica\GraphQL\Schema\Definition\Types\InputObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\ObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\BooleanType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\FloatType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\IDType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\IntType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\StringType;
use Efabrica\GraphQL\Schema\Definition\Types\Type;
use Efabrica\GraphQL\Schema\Definition\Values\Value;
use Efabrica\GraphQL\Schema\Transformers\WebonyxSchemaTransformer;
use GraphQL\Type\Definition\EnumType as WebonyxEnumType;
use GraphQL\Type\Definition\InputObjectType as WebonyxInputObjectType;
use GraphQL\Type\Definition\ObjectType as WebonyxObjectType;
use GraphQL\Type\Definition\Type as WebonyxType;
use GraphQL\Type\Schema as WebonyxSchema;
use GraphQL\Type\SchemaConfig as WebonyxSchemaConfig;
use GraphQL\Utils\SchemaPrinter;
use PHPUnit\Framework\TestCase;

class WebonyxSchemaTransformerTest extends TestCase
{
    private WebonyxSchemaTransformer $schemaTransformer;

    protected function setUp(): void
    {
        $this->schemaTransformer = new WebonyxSchemaTransformer();
    }

    public function testThrowsExceptionOnUnknownType(): void
    {
        $schema = (new Schema())
            ->setQuery(
                (new ObjectType('lorem'))
                    ->setFields([
                        new Field(
                            'ipsum',
                            new class extends Type {
                                public function __construct()
                                {
                                    parent::__construct('Ipsum');
                                }
                            }
                        ),
                    ])
            );

        $this->expectException(SchemaTransformerException::class);
        $this->schemaTransformer->handle($schema);
    }

    public function testCanTransformObjectTypeWithOnlyRequiredParameters(): void
    {
        $webonyxSchema = new WebonyxSchema(
            (new WebonyxSchemaConfig())
                ->setQuery(
                    new WebonyxObjectType([
                        'name' => 'lorem',
                        'fields' => [],
                    ])
                )
        );

        $schema = (new Schema())
            ->setQuery(
                new ObjectType('lorem')
            );

        $this->assertEquals(
            SchemaPrinter::doPrint($webonyxSchema),
            SchemaPrinter::doPrint($this->schemaTransformer->handle($schema))
        );
    }

    public function testCanTransformObjectTypeWithAllParameters(): void
    {
        $webonyxSchema = new WebonyxSchema(
            (new WebonyxSchemaConfig())
                ->setQuery(
                    new WebonyxObjectType([
                        'name' => 'lorem',
                        'fields' => [],
                        'description' => 'Quisque ut orci',
                    ])
                )
        );

        $schema = (new Schema())
            ->setQuery(
                (new ObjectType('lorem'))
                    ->setDescription('Quisque ut orci')
            );

        $this->assertEquals(
            SchemaPrinter::doPrint($webonyxSchema),
            SchemaPrinter::doPrint($this->schemaTransformer->handle($schema))
        );
    }

    public function testCanTransformFieldsWithOnlyRequiredParameters(): void
    {
        $webonyxSchema = new WebonyxSchema(
            (new WebonyxSchemaConfig())
                ->setQuery(
                    new WebonyxObjectType([
                        'name' => 'lorem',
                        'fields' => [
                            'ipsum' => WebonyxType::nonNull(WebonyxType::string()),
                        ],
                    ])
                )
        );

        $schema = (new Schema())
            ->setQuery(
                (new ObjectType('doris'))
                    ->setName('lorem')
                    ->setFields([
                        (new Field('psumi', new IntType()))
                            ->setName('ipsum')
                            ->setType(new StringType()),
                    ])
            );

        $this->assertEquals(
            SchemaPrinter::doPrint($webonyxSchema),
            SchemaPrinter::doPrint($this->schemaTransformer->handle($schema))
        );
    }

    public function testCanTransformFieldsWithAllParameters(): void
    {
        $webonyxSchema = new WebonyxSchema(
            (new WebonyxSchemaConfig())
                ->setQuery(
                    new WebonyxObjectType([
                        'name' => 'lorem',
                        'fields' => [
                            [
                                'name' => 'ipsum',
                                'description' => 'Lorem ipsum',
                                'type' => WebonyxType::listOf(WebonyxType::string()),
                                'args' => [],
                            ],
                        ],
                    ])
                )
        );

        $schema = (new Schema())
            ->setQuery(
                (new ObjectType('lorem'))
                    ->setFields([
                        (new Field('ipsum', new StringType()))
                            ->setDescription('Lorem ipsum')
                            ->setArguments([])
                            ->setMulti()
                            ->setNullable()
                            ->setMultiItemNullable()
                    ])
            );

        $this->assertEquals(
            SchemaPrinter::doPrint($webonyxSchema),
            SchemaPrinter::doPrint($this->schemaTransformer->handle($schema))
        );
    }

    public function testCanTransformFieldArgumentsWithOnlyRequiredParameters(): void
    {
        $webonyxSchema = new WebonyxSchema(
            (new WebonyxSchemaConfig())
                ->setQuery(
                    new WebonyxObjectType([
                        'name' => 'lorem',
                        'fields' => [
                            'ipsum' => [
                                'type' => WebonyxType::nonNull(WebonyxType::string()),
                                'args' => [
                                    'dolor' => WebonyxType::id(),
                                ],
                            ],
                        ],
                    ])
                )
        );

        $schema = (new Schema())
            ->setQuery(
                (new ObjectType('lorem'))
                    ->setFields([
                        (new Field('ipsum', new StringType()))
                            ->addArgument(
                                (new FieldArgument('doris', new IntType()))
                                    ->setName('dolor')
                                    ->setType(new IDType())
                            ),
                    ])
            );

        $this->assertEquals(
            SchemaPrinter::doPrint($webonyxSchema),
            SchemaPrinter::doPrint($this->schemaTransformer->handle($schema))
        );
    }

    public function testCanTransformFieldArgumentsWithAllParameters(): void
    {
        $webonyxSchema = new WebonyxSchema(
            (new WebonyxSchemaConfig())
                ->setQuery(
                    new WebonyxObjectType([
                        'name' => 'lorem',
                        'fields' => [
                            'ipsum' => [
                                'type' => WebonyxType::nonNull(WebonyxType::string()),
                                'args' => [
                                    'dolor' => [
                                        'type' => WebonyxType::id(),
                                        'description' => 'Dolor sit amet',
                                        'defaultValue' => 'istum',
                                    ],
                                ],
                            ],
                        ],
                    ])
                )
        );

        $schema = (new Schema())
            ->setQuery(
                (new ObjectType('lorem'))
                    ->addField(
                        (new Field('ipsum', new StringType()))
                            ->setArguments([
                                (new FieldArgument('dolor', new IDType()))
                                    ->setDescription('Dolor sit amet')
                                    ->setDefaultValue('istum'),
                            ]),
                    )
            );

        $this->assertEquals(
            SchemaPrinter::doPrint($webonyxSchema),
            SchemaPrinter::doPrint($this->schemaTransformer->handle($schema))
        );
    }

    public function testCanTransformEnumTypeWithOnlyRequiredParameters(): void
    {
        $webonyxSchema = new WebonyxSchema(
            (new WebonyxSchemaConfig())
                ->setQuery(
                    new WebonyxObjectType([
                        'name' => 'query',
                        'fields' => [
                            'episode' => new WebonyxEnumType([
                                'name' => 'Episode',
                                'values' => [
                                    'NEWHOPE' => [
                                        'value' => 4,
                                    ],
                                    'EMPIRE' => [
                                        'value' => 5,
                                    ],
                                    'JEDI' => [
                                        'value' => 6,
                                    ],
                                ],
                            ]),
                        ],
                    ])
                )
        );

        $schema = (new Schema())
            ->setQuery(
                (new ObjectType('query'))
                    ->setFields([
                        (new Field(
                            'episode',
                            (new EnumType('Episode'))
                                ->setValues([
                                    new Value('NEWHOPE', 4),
                                    new Value('EMPIRE', 5),
                                    new Value('JEDI', 6),
                                ])
                        ))->setNullable(),
                    ])
            );

        $this->assertEquals(
            SchemaPrinter::doPrint($webonyxSchema),
            SchemaPrinter::doPrint($this->schemaTransformer->handle($schema))
        );
    }

    public function testCanTransformEnumTypeWithAllParameters(): void
    {
        $webonyxSchema = new WebonyxSchema(
            (new WebonyxSchemaConfig())
                ->setQuery(
                    new WebonyxObjectType([
                        'name' => 'query',
                        'fields' => [
                            'episode' => new WebonyxEnumType([
                                'name' => 'Episode',
                                'description' => 'One of the films in the Star Wars Trilogy',
                                'values' => [
                                    'NEWHOPE' => [
                                        'value' => 4,
                                        'description' => 'Released in 1977.',
                                    ],
                                    'EMPIRE' => [
                                        'value' => 5,
                                        'description' => 'Released in 1980.',
                                    ],
                                    'JEDI' => [
                                        'value' => 6,
                                        'description' => 'Released in 1983.',
                                    ],
                                ],
                            ]),
                        ],
                    ])
                )
        );

        $schema = (new Schema())
            ->setQuery(
                (new ObjectType('query'))
                    ->setFields([
                        (new Field(
                            'episode',
                            (new EnumType('Episode'))
                                ->setDescription('One of the films in the Star Wars Trilogy')
                                ->setValues([
                                    (new Value('NEWHOPE', 4))
                                        ->setDescription('Released in 1977.'),
                                    (new Value('EMPIRE', 5))
                                        ->setDescription('Released in 1980.'),
                                ])
                                ->addValue(
                                    (new Value('JODA', 9))
                                        ->setName('JEDI')
                                        ->setValue(6)
                                        ->setDescription('Released in 1983.'),
                                )
                        ))->setNullable(),
                    ])
            );

        $this->assertEquals(
            SchemaPrinter::doPrint($webonyxSchema),
            SchemaPrinter::doPrint($this->schemaTransformer->handle($schema))
        );
    }

    public function testCanTransformScalarTypes(): void
    {
        $webonyxSchema = new WebonyxSchema(
            (new WebonyxSchemaConfig())
                ->setQuery(
                    new WebonyxObjectType([
                        'name' => 'query',
                        'fields' => [
                            'id' => WebonyxType::id(),
                            'string' => WebonyxType::string(),
                            'int' => WebonyxType::int(),
                            'float' => WebonyxType::float(),
                            'boolean' => WebonyxType::boolean(),
                        ],
                    ])
                )
        );

        $schema = (new Schema())
            ->setQuery(
                (new ObjectType('query'))
                    ->setFields([
                        (new Field('id', new IDType()))
                            ->setNullable(),
                        (new Field('string', new StringType()))
                            ->setNullable(),
                        (new Field('int', new IntType()))
                            ->setNullable(),
                        (new Field('float', new FloatType()))
                            ->setNullable(),
                        (new Field('boolean', new BooleanType()))
                            ->setNullable(),
                    ])
            );

        $this->assertEquals(
            SchemaPrinter::doPrint($webonyxSchema),
            SchemaPrinter::doPrint($this->schemaTransformer->handle($schema))
        );
    }

    public function testCanTransformInputObjectTypes(): void
    {
        $webonyxSchema = new WebonyxSchema(
            (new WebonyxSchemaConfig())
                ->setQuery(
                    new WebonyxObjectType([
                        'name' => 'query',
                        'fields' => [
                            'stories' => [
                                'type' => WebonyxType::string(),
                                'args' => [
                                    'filters' => [
                                        'type' => new WebonyxInputObjectType([
                                            'name' => 'StoryFiltersInput',
                                            'description' => 'Lorem Ipsum',
                                            'fields' => [
                                                'author' => [
                                                    'type' => WebonyxType::id(),
                                                ],
                                                'popular' => [
                                                    'type' => WebonyxType::boolean(),
                                                ],
                                                'tags' => [
                                                    'type' => WebonyxType::listOf(WebonyxType::string()),
                                                ],
                                            ],
                                        ]),
                                    ],
                                ],
                            ],
                        ],
                    ])
                )
        );

        $schema = (new Schema())
            ->setQuery(
                (new ObjectType('query'))
                    ->setFields([
                        (new Field('stories', new StringType()))
                            ->setArguments([
                                (new FieldArgument(
                                    'filters',
                                    (new InputObjectType('StoryFiltersInput'))
                                        ->setDescription('Lorem Ipsum')
                                        ->setFields([
                                            (new Field('author', new IDType()))
                                                ->setNullable(),
                                            (new Field('popular', new BooleanType()))
                                                ->setNullable(),
                                            (new Field('tags', new StringType()))
                                                ->setMulti()
                                                ->setNullable()
                                                ->setMultiItemNullable(),
                                        ])
                                )),
                            ])
                            ->setNullable(),
                    ])
            );

        $this->assertEquals(
            SchemaPrinter::doPrint($webonyxSchema),
            SchemaPrinter::doPrint($this->schemaTransformer->handle($schema))
        );
    }

    public function testCanTransformSchema(): void
    {
        $this->assertEquals(
            SchemaPrinter::doPrint(
                new WebonyxSchema(
                    (new WebonyxSchemaConfig())
                        ->setQuery(
                            new WebonyxObjectType([
                                'name' => 'Query',
                                'fields' => [
                                    'echo' => [
                                        'type' => WebonyxType::string(),
                                        'args' => [
                                            'message' => ['type' => WebonyxType::string()],
                                        ],
                                        'resolve' => static fn(
                                            $rootValue,
                                            array $args
                                        ): string => $rootValue['prefix'] . $args['message'],
                                    ],
                                ],
                            ])
                        )
                )
            ),
            SchemaPrinter::doPrint(
                $this->schemaTransformer->handle(
                    (new Schema())
                        ->setQuery(
                            (new ObjectType('Query'))
                                ->setFields([
                                    (new Field('echo', new StringType()))
                                        ->setArguments([
                                            new FieldArgument('message', new StringType()),
                                        ])
                                        ->setResolver(
                                            new class implements ResolverInterface {
                                                public function __invoke(
                                                    $parentValue,
                                                    array $args,
                                                    ResolveInfo $resolveInfo
                                                ): string {
                                                    return $parentValue['prefix'] . $args['message'];
                                                }
                                            }
                                        )
                                        ->setNullable(),
                                ])
                        )
                )
            )
        );
    }
}
