<?php

namespace Efabrica\GraphQL\Schema\Loaders;

use Efabrica\GraphQL\Schema\Definition\Schema;

interface SchemaLoaderInterface
{
    public function getSchema(): Schema;
}
