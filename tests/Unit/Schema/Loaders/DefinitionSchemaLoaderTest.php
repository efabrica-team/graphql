<?php

namespace Tests\Unit\Schema\Loaders;

use Efabrica\GraphQL\Schema\Definition\Schema;
use Efabrica\GraphQL\Schema\Definition\Types\ObjectType;
use Efabrica\GraphQL\Schema\Loaders\DefinitionSchemaLoader;
use PHPUnit\Framework\TestCase;

class DefinitionSchemaLoaderTest extends TestCase
{
    private Schema $schema;

    protected function setUp(): void
    {
        $this->schema = (new Schema())
            ->setQuery(
                new ObjectType('Query')
            );
    }

    public function testCanGetSchemaFromObject(): void
    {
        $definitionSchemaLoader = new DefinitionSchemaLoader($this->schema);
        $this->assertSame($this->schema, $definitionSchemaLoader->getSchema());
    }

    public function testCanGetSchemaFromCallable(): void
    {
        $definitionSchemaLoader = new DefinitionSchemaLoader(fn() => $this->schema);
        $this->assertSame($this->schema, $definitionSchemaLoader->getSchema());
    }
}
