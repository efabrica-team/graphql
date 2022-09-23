<?php

namespace Efabrica\GraphQL\Schema\Definition\Types\Scalar;

class BooleanType extends ScalarType
{
    public function __construct()
    {
        parent::__construct('Boolean');
    }
}
