<?php

namespace Efabrica\GraphQL\Schema\Custom\Fields;

use Efabrica\GraphQL\Schema\Custom\Types\HavingType;
use Efabrica\GraphQL\Schema\Definition\Fields\InputObjectField;

class HavingAndField extends InputObjectField
{
    public const NAME = 'having';

    public function __construct()
    {
        parent::__construct(
            self::NAME,
            new HavingType()
        );

        $this->setMulti()
            ->setNullable();
    }
}
