<?php

declare(strict_types=1);

namespace Platine\Test\Pagination;

use Platine\Pagination\Page;
use Platine\PlatineTestCase;

/**
 * Page class tests
 *
 * @group core
 * @group pagination
 */
class PageTest extends PlatineTestCase
{

    public function testPageNumberIsNumeric(): void
    {
        $s = new Page(2, '/page/2', true);

        $this->assertEquals(2, $s->getNumber());
        $this->assertEquals('/page/2', $s->getUrl());
        $this->assertTrue($s->isCurrent());
    }

    public function testPageNumberIsString(): void
    {
        $s = new Page('...', null, false);

        $this->assertEquals('...', $s->getNumber());
        $this->assertNull($s->getUrl());
        $this->assertFalse($s->isCurrent());
    }
}
