<?php

namespace Tests\Feature;

use Efabrica\GraphQL\Drivers\WebonyxDriver;
use Efabrica\GraphQL\GraphQL;
use Efabrica\GraphQL\Resolvers\ResolverInterface;
use Efabrica\GraphQL\Schema\Definition\Arguments\FieldArgument;
use Efabrica\GraphQL\Schema\Definition\Fields\Field;
use Efabrica\GraphQL\Schema\Definition\ResolveInfo;
use Efabrica\GraphQL\Schema\Definition\Schema;
use Efabrica\GraphQL\Schema\Definition\Types\EnumType;
use Efabrica\GraphQL\Schema\Definition\Types\ObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\StringType;
use Efabrica\GraphQL\Schema\Definition\Values\Value;
use Efabrica\GraphQL\Schema\Loaders\DefinitionSchemaLoader;
use PHPUnit\Framework\TestCase;

class GraphQLTest extends TestCase
{
    public function testCanExecuteQuery(): void
    {
        $graphQL = $this->createServer(
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
                                            return $args['message'];
                                        }
                                    }
                                )
                                ->setNullable(),
                        ])
                )
        );

        $this->assertSame([
            'data' => [
                'echo' => 'Hello World',
            ],
        ], $graphQL->executeQuery('{ echo(message: "Hello World") }'));
    }

    public function testCanGetDefaultValueFromField(): void
    {
        $graphQL = $this->createServer(
            (new Schema())
                ->setQuery(
                    (new ObjectType('Query'))
                        ->setFields([
                            (new Field('echo', new StringType()))
                                ->setArguments([
                                    (new FieldArgument('message', new StringType()))
                                        ->setDefaultValue('Hello World'),
                                ])
                                ->setResolver(
                                    new class implements ResolverInterface {
                                        public function __invoke(
                                            $parentValue,
                                            array $args,
                                            ResolveInfo $resolveInfo
                                        ): ?string {
                                            return $args['message'];
                                        }
                                    }
                                )
                                ->setNullable(),
                        ])
                )
        );

        $this->assertSame([
            'data' => [
                'echo' => 'Hello World',
            ],
        ], $graphQL->executeQuery('{ echo }'));
    }

    public function testCanGetEnumValue(): void
    {
        $graphQL = $this->createServer(
            (new Schema())
                ->setQuery(
                    (new ObjectType('Query'))
                        ->setFields([
                            (new Field('echo', new StringType()))
                                ->setArguments([
                                    (new FieldArgument(
                                        'message',
                                        (new EnumType('greeting'))
                                            ->setValues([
                                                new Value('HELLO', 'hello'),
                                                (new Value('HELLO', 'hello'))
                                                    ->setName('HEY')
                                                    ->setValue('hi'),
                                            ])
                                    )),
                                ])
                                ->setResolver(
                                    new class implements ResolverInterface {
                                        public function __invoke(
                                            $parentValue,
                                            array $args,
                                            ResolveInfo $resolveInfo
                                        ): ?string {
                                            return $args['message'];
                                        }
                                    }
                                )
                                ->setNullable(),
                        ])
                )
        );

        $this->assertSame([
            'data' => [
                'echo' => 'hello',
            ],
        ], $graphQL->executeQuery('{ echo (message: HELLO) }'));

        $this->assertSame([
            'data' => [
                'echo' => 'hi',
            ],
        ], $graphQL->executeQuery('{ echo (message: HEY) }'));
    }

    private function createServer(Schema $schema): GraphQL
    {
        $schemaLoader = new DefinitionSchemaLoader($schema);
        $driver = new WebonyxDriver($schemaLoader);
        return new GraphQL($driver);
    }
}
