<?php

namespace Efabrica\GraphQL\Schema\Definition\Types\Scalar;

class IntType extends ScalarType
{
    public function __construct()
    {
        parent::__construct('Int');
    }
}
