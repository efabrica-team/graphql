<?php

namespace Efabrica\GraphQL\Exceptions;

class ResolverException extends GraphQLException
{
    public function getCategory(): string
    {
        return 'resolver';
    }
}
