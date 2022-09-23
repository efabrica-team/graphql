<?php

namespace Efabrica\GraphQL\Schema\Custom\Fields;

use Efabrica\GraphQL\Schema\Definition\Fields\InputObjectField;
use Efabrica\GraphQL\Schema\Definition\Types\InputObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\StringType;

class GroupField extends InputObjectField
{
    public const NAME = 'group';

    public const FIELD_COLUMN = 'column';

    public function __construct()
    {
        parent::__construct(
            self::NAME,
            (new InputObjectType('group_type'))
                ->setFields([
                    new InputObjectField(self::FIELD_COLUMN, new StringType()),
                ])
        );
        $this->setMulti()
            ->setNullable();
    }
}
