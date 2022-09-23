<?php

namespace Efabrica\GraphQL\Schema\Custom\Types;

use Efabrica\GraphQL\Schema\Custom\Fields\WhereAndField;
use Efabrica\GraphQL\Schema\Custom\Fields\WhereOrField;
use Efabrica\GraphQL\Schema\Definition\Fields\InputObjectField;
use Efabrica\GraphQL\Schema\Definition\Types\InputObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\StringType;

class WhereType extends InputObjectType
{
    public const FIELD_COLUMN = 'column';
    public const FIELD_VALUE = 'value';
    public const FIELD_COMPARATOR = 'comparator';

    public function __construct()
    {
        parent::__construct(
            'where_type',
        );

        $this->setFields(fn() => [
            new WhereAndField(),
            new WhereOrField(),
            (new InputObjectField(self::FIELD_COLUMN, new StringType()))
                ->setNullable(),
            (new InputObjectField(self::FIELD_VALUE, new StringType()))
                ->setMulti()
                ->setNullable(),
            (new InputObjectField(self::FIELD_COMPARATOR, new WhereComparatorEnum()))
                ->setDefaultValue(WhereComparatorEnum::EQUAL),
        ]);
    }
}
