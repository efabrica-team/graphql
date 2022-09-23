<?php

namespace Efabrica\GraphQL\Schema\Definition\Types\Scalar;

class FloatType extends ScalarType
{
    public function __construct()
    {
        parent::__construct('Float');
    }
}
