<?php

namespace Efabrica\GraphQL\Schema\Definition\Types\Scalar;

class IDType extends ScalarType
{
    public function __construct()
    {
        parent::__construct('ID');
    }
}
