<?php

namespace Efabrica\GraphQL\Schema\Custom\Arguments;

use Efabrica\GraphQL\Schema\Definition\Arguments\FieldArgument;
use Efabrica\GraphQL\Schema\Definition\Fields\InputObjectField;
use Efabrica\GraphQL\Schema\Definition\Types\InputObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\IntType;

class PaginationArgument extends FieldArgument
{
    public const NAME = 'pagination';

    public const FIELD_LIMIT = 'limit';
    public const FIELD_OFFSET = 'offset';

    public function __construct()
    {
        parent::__construct(
            self::NAME,
            (new InputObjectType('pagination_argument'))
                ->setFields([
                    (new InputObjectField(self::FIELD_LIMIT, new IntType()))
                        ->setNullable(),
                    (new InputObjectField(self::FIELD_OFFSET, new IntType()))
                        ->setNullable(),
                ])
        );
    }
}
