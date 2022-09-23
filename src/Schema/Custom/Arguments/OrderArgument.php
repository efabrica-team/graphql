<?php

namespace Efabrica\GraphQL\Schema\Custom\Arguments;

use Efabrica\GraphQL\Schema\Custom\Types\OrderDirectionEnum;
use Efabrica\GraphQL\Schema\Definition\Arguments\FieldArgument;
use Efabrica\GraphQL\Schema\Definition\Fields\InputObjectField;
use Efabrica\GraphQL\Schema\Definition\Types\InputObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\StringType;

class OrderArgument extends FieldArgument
{
    public const NAME = 'order';

    public const FIELD_KEY = 'key';
    public const FIELD_ORDER = 'order';

    public function __construct()
    {
        parent::__construct(
            self::NAME,
            (new InputObjectType('order_argument'))
                ->setFields([
                    new InputObjectField(self::FIELD_KEY, new StringType()),
                    (new InputObjectField(self::FIELD_ORDER, new OrderDirectionEnum()))
                        ->setDefaultValue(OrderDirectionEnum::ASC)
                        ->setNullable(),
                ])
        );

        $this->setMulti();
    }
}
