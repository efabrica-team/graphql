<?php

namespace Tests\Unit\Helpers;

use Efabrica\GraphQL\Helpers\DatabaseColumnTypeTransformer;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\IntType;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\StringType;
use PHPUnit\Framework\TestCase;

class DatabaseColumnTypeTransformerTest extends TestCase
{
    public function testCanReturnExistingType(): void
    {
        $databaseColumnTypeTransformer = new DatabaseColumnTypeTransformer();
        $this->assertEquals(new IntType(), $databaseColumnTypeTransformer->handle('INT'));
    }

    public function testReturnStringTypeOnNonExistingType(): void
    {
        $databaseColumnTypeTransformer = new DatabaseColumnTypeTransformer();
        $this->assertEquals(new StringType(), $databaseColumnTypeTransformer->handle('LOREM'));
    }
}
