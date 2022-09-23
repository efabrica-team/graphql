<?php

namespace Efabrica\GraphQL\Schema\Custom\Fields;

use Efabrica\GraphQL\Schema\Custom\Types\WhereType;
use Efabrica\GraphQL\Schema\Definition\Fields\InputObjectField;

class WhereOrField extends InputObjectField
{
    public const NAME = 'where_or';

    public function __construct()
    {
        parent::__construct(
            self::NAME,
            new WhereType()
        );

        $this->setName(self::NAME)
            ->setMulti()
            ->setNullable();
    }
}
