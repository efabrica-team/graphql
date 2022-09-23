<?php

namespace Tests\Unit\Schema\Definition;

use Efabrica\GraphQL\Schema\Definition\HasSettigns;
use PHPUnit\Framework\TestCase;

class HasSettignsTest extends TestCase
{
    private object $settingable;

    protected function setUp(): void
    {
        $this->settingable = new class {
            use HasSettigns;
        };
    }

    public function testCanSetAndGetSettings(): void
    {
        $this->settingable->setSetting('lorem', 'ipsum');
        $this->assertSame('ipsum', $this->settingable->getSetting('lorem'));

        $this->settingable->setSettings([
            'dolor' => 'sit',
            'amet' => 'consectetur',
        ]);
        $this->assertSame([
            'dolor' => 'sit',
            'amet' => 'consectetur',
        ], $this->settingable->getSettings());

        $this->assertNull($this->settingable->getSetting('non-existent'));
        $this->assertSame('amar', $this->settingable->getSetting('non-existent', 'amar'));
    }
}
