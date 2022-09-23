<?php

namespace Efabrica\GraphQL\Schema\Custom\Types;

use Efabrica\GraphQL\Schema\Definition\Types\EnumType;
use Efabrica\GraphQL\Schema\Definition\Values\Value;

class OrderDirectionEnum extends EnumType
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';
    public const RAND = 'RAND';

    public function __construct()
    {
        parent::__construct('order_direction');

        $this->setValues([
            new Value(self::ASC, self::ASC),
            new Value(self::DESC, self::DESC),
            new Value(self::RAND, self::RAND),
        ]);
    }
}
