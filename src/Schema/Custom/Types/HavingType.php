<?php

namespace Efabrica\GraphQL\Schema\Custom\Types;

use Efabrica\GraphQL\Schema\Custom\Fields\HavingAndField;
use Efabrica\GraphQL\Schema\Custom\Fields\HavingOrField;
use Efabrica\GraphQL\Schema\Definition\Fields\InputObjectField;
use Efabrica\GraphQL\Schema\Definition\Types\InputObjectType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\StringType;

class HavingType extends InputObjectType
{
    public const FIELD_COLUMN = 'column';
    public const FIELD_VALUE = 'value';
    public const FIELD_COMPARATOR = 'comparator';

    public function __construct()
    {
        parent::__construct(
            'having_type',
        );

        $this->setFields(fn() => [
            new HavingAndField(),
            new HavingOrField(),
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
