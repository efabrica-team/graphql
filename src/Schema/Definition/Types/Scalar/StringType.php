<?php

namespace Efabrica\GraphQL\Schema\Definition\Types\Scalar;

class StringType extends ScalarType
{
    public function __construct()
    {
        parent::__construct('String');
    }
}
