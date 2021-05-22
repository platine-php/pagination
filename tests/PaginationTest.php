<?php

declare(strict_types=1);

namespace Platine\Test\Pagination;

use Platine\Pagination\Pagination;
use Platine\Pagination\Renderer\DefaultRenderer;
use Platine\Pagination\RendererInterface;
use Platine\Pagination\UrlGenerator\SimpleUrlGenerator;
use Platine\Pagination\UrlGeneratorInterface;
use Platine\PlatineTestCase;

/**
 * Pagination class tests
 *
 * @group core
 * @group pagination
 */
class PaginationTest extends PlatineTestCase
{

    public function testConstructor(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(SimpleUrlGenerator::class);
        $s = new Pagination($urlGenerator, $renderer);
        $this->assertInstanceOf(Pagination::class, $s);
        $this->assertInstanceOf(RendererInterface::class, $s->getRenderer());
        $this->assertInstanceOf(UrlGeneratorInterface::class, $s->getUrlGenerator());
    }
}
