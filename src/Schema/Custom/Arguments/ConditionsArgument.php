<?php

namespace Efabrica\GraphQL\Schema\Custom\Arguments;

use Efabrica\GraphQL\Schema\Custom\Fields\GroupField;
use Efabrica\GraphQL\Schema\Custom\Fields\WhereAndField;
use Efabrica\GraphQL\Schema\Custom\Fields\WhereOrField;
use Efabrica\GraphQL\Schema\Definition\Arguments\FieldArgument;
use Efabrica\GraphQL\Schema\Definition\Types\InputObjectType;

class ConditionsArgument extends FieldArgument
{
    public const NAME = 'conditions';

    public function __construct()
    {
        parent::__construct(
            self::NAME,
            (new InputObjectType('condition_argument'))
                ->setFields([
                    new WhereAndField(),
                    new WhereOrField(),
                    new GroupField(),
                ])
        );
    }
}
