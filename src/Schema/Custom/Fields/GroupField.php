<?php

namespace Efabrica\GraphQL\Schema\Custom\Fields;

use Efabrica\GraphQL\Schema\Custom\Types\GroupType;
use Efabrica\GraphQL\Schema\Definition\Fields\InputObjectField;

class GroupField extends InputObjectField
{
    public const NAME = 'group';

    /**
     * TODO: remove in 0.3.0
     * @deprecated Use GroupType::FIELD_COLUMN instead
     */
    public const FIELD_COLUMN = GroupType::FIELD_COLUMN;

    public function __construct()
    {
        parent::__construct(
            self::NAME,
            new GroupType()
        );
        $this->setMulti()
            ->setNullable();
    }
}
