<?php

declare(strict_types=1);

namespace Platine\Test\Pagination\UrlGenerator;

use Platine\Pagination\UrlGenerator\SimpleUrlGenerator;
use Platine\Dev\PlatineTestCase;

/**
 * SimpleUrlGenerator class tests
 *
 * @group core
 * @group pagination
 */
class SimpleUrlGeneratorTest extends PlatineTestCase
{
    public function testConstructorDefault(): void
    {
        $s = new SimpleUrlGenerator();
        $this->assertInstanceOf(SimpleUrlGenerator::class, $s);
        $this->assertEquals('/?page=(num)', $this->getPropertyValue(SimpleUrlGenerator::class, $s, 'urlPattern'));
    }

    public function testConstructorCustomUrlPattern(): void
    {
        $s = new SimpleUrlGenerator('/page/(num)');
        $this->assertInstanceOf(SimpleUrlGenerator::class, $s);
        $this->assertEquals('/page/(num)', $this->getPropertyValue(
            SimpleUrlGenerator::class,
            $s,
            'urlPattern'
        ));
    }

    public function testGetUrlPattern(): void
    {
        $s = new SimpleUrlGenerator('/page/(num)');
        $this->assertEquals('/page/(num)', $s->getUrlPattern());
    }

    public function testGeneratePageUrl(): void
    {
        $s = new SimpleUrlGenerator('/page/(num)');
        $this->assertInstanceOf(SimpleUrlGenerator::class, $s);

        $this->assertEquals('/page/1', $s->generatePageUrl(1));
        $this->assertEquals('/page/2', $s->generatePageUrl(2));
        $this->assertEquals('/page/3', $s->generatePageUrl(3));
        $this->assertEquals('/page/4', $s->generatePageUrl(4));

        $s1 = new SimpleUrlGenerator('/pages');

        $this->assertEquals('/pages', $s1->generatePageUrl(4));
    }
}
