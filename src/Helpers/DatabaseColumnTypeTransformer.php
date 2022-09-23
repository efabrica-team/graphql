<?php

namespace Efabrica\GraphQL\Helpers;

use Efabrica\GraphQL\Schema\Definition\Types\Scalar\BooleanType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\FloatType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\IntType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\StringType;
use Efabrica\GraphQL\Schema\Definition\Types\Type;

final class DatabaseColumnTypeTransformer
{
    public function handle(string $type): Type
    {
        $types = [
            'BIT' => StringType::class,
            'INT' => IntType::class,
            'INTEGER' => IntType::class,
            'TINYINT' => IntType::class,
            'SMALLINT' => IntType::class,
            'MEDIUMINT' => IntType::class,
            'BIGINT' => IntType::class,

            'DECIMAL' => FloatType::class,
            'NUMERIC' => FloatType::class,
            'FLOAT' => FloatType::class,
            'DOUBLE' => FloatType::class,

            'CHAR' => StringType::class,
            'VARCHAR' => StringType::class,
            'BINARY' => StringType::class,
            'VARBINARY' => StringType::class,
            'BLOB' => StringType::class,
            'TEXT' => StringType::class,
            'ENUM' => StringType::class,
            'SET' => StringType::class,

            'DATE' => StringType::class,
            'DATETIME' => StringType::class,
            'TIMESTAMP' => StringType::class,
            'TIME' => StringType::class,
            'YEAR' => StringType::class,

            'JSON' => StringType::class,
        ];

        $type = $types[$type] ?? StringType::class;

        return new $type;
    }
}
