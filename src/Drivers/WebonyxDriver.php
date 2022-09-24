<?php

namespace Efabrica\GraphQL\Drivers;

use Efabrica\GraphQL\Schema\Loaders\SchemaLoaderInterface;
use Efabrica\GraphQL\Schema\Transformers\WebonyxSchemaTransformer;
use GraphQL\GraphQL as WebonyxGraphQl;

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
        return WebonyxGraphQl::executeQuery(
            $this->schemaTransformer->handle($this->schemaLoader->getSchema()),
            $query
        )->toArray();
    }
}
