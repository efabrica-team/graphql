<?php

namespace Efabrica\GraphQL\Resolvers;

use Efabrica\GraphQL\Schema\Definition\ResolveInfo;

interface ResolverInterface
{
    public function __invoke($parentValue, array $args, ResolveInfo $resolveInfo);
}
