<?php

namespace Efabrica\GraphQL\Schema\Loaders;

use Efabrica\GraphQL\Schema\Definition\Schema;

class DefinitionSchemaLoader implements SchemaLoaderInterface
{
    /** @var Schema|callable */
    private $schema;

    /**
     * @param Schema|callable $schema
     */
    public function __construct($schema)
    {
        $this->schema = $schema;
    }

    public function getSchema(): Schema
    {
        if (is_callable($this->schema)) {
            $this->schema = call_user_func($this->schema);
        }

        return $this->schema;
    }
}
