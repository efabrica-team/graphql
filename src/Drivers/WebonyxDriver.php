<?php

namespace Efabrica\GraphQL\Drivers;

use Efabrica\GraphQL\Schema\Loaders\SchemaLoaderInterface;
use Efabrica\GraphQL\Schema\Transformers\WebonyxSchemaTransformer;
use GraphQL\Error\DebugFlag;
use GraphQL\GraphQL as WebonyxGraphQl;
use GraphQL\Type\Schema;

final class WebonyxDriver implements DriverInterface
{
    private SchemaLoaderInterface $schemaLoader;

    private WebonyxSchemaTransformer $schemaTransformer;

    public function __construct(SchemaLoaderInterface $schemaLoader)
    {
        $this->schemaLoader = $schemaLoader;
        $this->schemaTransformer = new WebonyxSchemaTransformer();
    }

    public function executeQuery(string $query): array
    {
        return WebonyxGraphQl::executeQuery($this->getSchema(), $query)->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE);
    }

    private function getSchema(): Schema
    {
        return $this->schemaTransformer->handle($this->schemaLoader->getSchema());
    }
}
