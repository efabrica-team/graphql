<?php

namespace Tests\Unit\Schema\Definition;

use Efabrica\GraphQL\Schema\Definition\Fields\Field;
use Efabrica\GraphQL\Schema\Definition\ResolveInfo;
use Efabrica\GraphQL\Schema\Definition\Types\Scalar\StringType;
use PHPUnit\Framework\TestCase;

class ResolveInfoTest extends TestCase
{
    public function testCanSetAndGetProperties(): void
    {
        $field = new Field('lorem', new StringType());
        $resolveInfo = new ResolveInfo(
            $field,
            ['lorem', 'ipsum', 'dolor'],
            ['sit', 'amet']
        );

        $this->assertSame($field, $resolveInfo->getField());
        $this->assertSame(['lorem', 'ipsum', 'dolor'], $resolveInfo->getPath());
        $this->assertSame(['sit', 'amet'], $resolveInfo->getFieldSelection());

        $field = new Field('ipsum', new StringType());
        $resolveInfo->setField($field);
        $resolveInfo->setPath(['dolor', 'ipsum', 'lorem']);
        $resolveInfo->setFieldSelection(['amet', 'sit']);

        $this->assertSame($field, $resolveInfo->getField());
        $this->assertSame(['dolor', 'ipsum', 'lorem'], $resolveInfo->getPath());
        $this->assertSame(['amet', 'sit'], $resolveInfo->getFieldSelection());
    }
}
