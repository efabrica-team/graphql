<?php

namespace Efabrica\GraphQL\Schema\Custom\Types;

use Efabrica\GraphQL\Schema\Definition\Types\EnumType;
use Efabrica\GraphQL\Schema\Definition\Values\Value;

class WhereComparatorEnum extends EnumType
{
    public const EQUAL = 'EQUAL';

    public const NOT_EQUAL = 'NOT_EQUAL';

    public const IN = 'IN';

    public const NOT_IN = 'NOT_IN';

    public const LESS_THAN = 'LESS_THAN';

    public const LESS_THAN_EQUAL = 'LESS_THAN_EQUAL';

    public const MORE_THAN = 'MORE_THAN';

    public const MORE_THAN_EQUAL = 'MORE_THAN_EQUAL';

    public const LIKE = 'LIKE';

    public const NOT_LIKE = 'NOT_LIKE';

    public const NULL = 'NULL';

    public const NOT_NULL = 'NOT_NULL';

    public function __construct()
    {
        parent::__construct('where_comparator');
        $this->setValues([
            new Value(self::EQUAL, self::EQUAL),
            new Value(self::NOT_EQUAL, self::NOT_EQUAL),
            new Value(self::IN, self::IN),
            new Value(self::NOT_IN, self::NOT_IN),
            new Value(self::LESS_THAN, self::LESS_THAN),
            new Value(self::LESS_THAN_EQUAL, self::LESS_THAN_EQUAL),
            new Value(self::MORE_THAN, self::MORE_THAN),
            new Value(self::MORE_THAN_EQUAL, self::MORE_THAN_EQUAL),
            new Value(self::LIKE, self::LIKE),
            new Value(self::NOT_LIKE, self::NOT_LIKE),
            new Value(self::NULL, self::NULL),
            new Value(self::NOT_NULL, self::NOT_NULL),
        ]);
    }
}
