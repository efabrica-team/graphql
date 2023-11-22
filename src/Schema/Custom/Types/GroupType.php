<?php

namespace Efabrica\GraphQL\Schema\Custom\Types;

use Efabrica\GraphQL\Schema\Definition\Fields\InputObjectField;
use Efabrica\GraphQL\Schema\Definition\Types\InputObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\StringType;

class GroupType extends InputObjectType
{
    public const FIELD_COLUMN = 'column';

    public function __construct()
    {
        parent::__construct(
            'group_type',
        );

        $this->setFields(fn() => [
            new InputObjectField(self::FIELD_COLUMN, new StringType()),
        ]);
    }
}
