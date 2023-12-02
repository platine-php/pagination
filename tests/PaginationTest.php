<?php

declare(strict_types=1);

namespace Platine\Test\Pagination;

use InvalidArgumentException;
use Platine\Pagination\Page;
use Platine\Pagination\Pagination;
use Platine\Pagination\Renderer\DefaultRenderer;
use Platine\Pagination\RendererInterface;
use Platine\Pagination\UrlGenerator\SimpleUrlGenerator;
use Platine\Pagination\UrlGeneratorInterface;
use Platine\Dev\PlatineTestCase;

/**
 * Pagination class tests
 *
 * @group core
 * @group pagination
 */
class PaginationTest extends PlatineTestCase
{
    public function testConstructorDefault(): void
    {
        $s = new Pagination();
        $this->assertInstanceOf(Pagination::class, $s);
        $this->assertInstanceOf(RendererInterface::class, $s->getRenderer());
        $this->assertInstanceOf(UrlGeneratorInterface::class, $s->getUrlGenerator());
    }

    public function testConstructor(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(SimpleUrlGenerator::class);
        $s = new Pagination($urlGenerator, $renderer);
        $this->assertInstanceOf(Pagination::class, $s);
        $this->assertInstanceOf(RendererInterface::class, $s->getRenderer());
        $this->assertInstanceOf(UrlGeneratorInterface::class, $s->getUrlGenerator());
    }

    public function testSetRendererAndUrlGenerator(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(SimpleUrlGenerator::class);
        $s = new Pagination();

        $s->setRenderer($renderer);
        $s->setUrlGenerator($urlGenerator);
        $this->assertInstanceOf(Pagination::class, $s);
        $this->assertInstanceOf(RendererInterface::class, $s->getRenderer());
        $this->assertInstanceOf(UrlGeneratorInterface::class, $s->getUrlGenerator());
    }

    public function testPreviousNext(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(
            SimpleUrlGenerator::class,
            [
              'generatePageUrl' => 'my_url'
            ]
        );
        $s = new Pagination($urlGenerator, $renderer);

        $s->setTotalItems(100)
           ->setCurrentPage(2)
            ->setPreviousText('Foo')
            ->setNextText('Bar');

        $this->assertEquals(1, $s->getPreviousPage());
        $this->assertEquals(3, $s->getNextPage());
        $this->assertEquals('Foo', $s->getPreviousText());
        $this->assertEquals('Bar', $s->getNextText());
        $this->assertEquals('my_url', $s->getNextUrl());
        $this->assertEquals('my_url', $s->getPreviousUrl());
    }

    public function testGetInfo(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(
            SimpleUrlGenerator::class,
            [
              'generatePageUrl' => 'my_url'
            ]
        );
        $s = new Pagination($urlGenerator, $renderer);

        $s->setTotalItems(100)
           ->setCurrentPage(2)
            ->setPreviousText('Foo')
            ->setNextText('Bar');

        $this->assertEquals('{"offset":10,"limit":10,"total_items":100,"total_page"'
                . ':10,"page":2,"pages":[1,2,3,4,5,6,7,8,9,10],"next":3,'
                . '"previous":1,"url":""}', json_encode($s->getInfo()));
    }

    public function testPreviousNextNull(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(
            SimpleUrlGenerator::class,
            [
              'generatePageUrl' => 'my_url'
            ]
        );
        $s = new Pagination($urlGenerator, $renderer);

        $s->setTotalItems(10)
           ->setCurrentPage(1)
           ->setItemsPerPage(10);

        $this->assertNull($s->getPreviousPage());
        $this->assertNull($s->getNextPage());
        $this->assertNull($s->getNextUrl());
        $this->assertNull($s->getPreviousUrl());
    }

    public function testGetters(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(SimpleUrlGenerator::class);
        $s = new Pagination($urlGenerator, $renderer);

        $s->setTotalItems(100)
           ->setCurrentPage(3)
           ->setItemsPerPage(10);

        $this->assertEquals(100, $s->getTotalItems());
        $this->assertEquals(10, $s->getItemsPerPage());
        $this->assertEquals(3, $s->getCurrentPage());
        $this->assertEquals(20, $s->getOffset());
        $this->assertEquals(10, $s->getTotalPages());
        $this->assertEquals(10, $s->getMaxPages());

        //Invalid items per page
        $s->setItemsPerPage(-1);
        $this->assertEquals(0, $s->getTotalPages());
    }

    public function testSetMaxPagesSuccess(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(SimpleUrlGenerator::class);
        $s = new Pagination($urlGenerator, $renderer);

        $s->setMaxPages(7);

        $this->assertEquals(7, $s->getMaxPages());
    }

    public function testSetMaxPagesError(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(SimpleUrlGenerator::class);
        $s = new Pagination($urlGenerator, $renderer);

        $this->expectException(InvalidArgumentException::class);

        $s->setMaxPages(1);
    }

    public function testRenderAndToString(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class, [
            'render' => 'my_links'
        ]);
        $urlGenerator = $this->getMockInstance(SimpleUrlGenerator::class);
        $s = new Pagination($urlGenerator, $renderer);

        $res = $s->render();
        $this->assertEquals('my_links', $res);
        $this->assertEquals('my_links', $s->__toString());
    }

    public function testGetPages(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(SimpleUrlGenerator::class);
        $s = new Pagination($urlGenerator, $renderer);

        $s->setTotalItems(20)
           ->setCurrentPage(3)
           ->setItemsPerPage(10);

        $pages = $s->getPages();

        $this->assertIsArray($pages);
        $this->assertCount(2, $pages);
        $this->assertInstanceOf(Page::class, $pages[0]);
        $this->assertInstanceOf(Page::class, $pages[1]);
    }

    public function testGetPagesOnlyOnePage(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(SimpleUrlGenerator::class);
        $s = new Pagination($urlGenerator, $renderer);

        $s->setTotalItems(10)
           ->setCurrentPage(3)
           ->setItemsPerPage(10);

        $pages = $s->getPages();

        $this->assertIsArray($pages);
        $this->assertEmpty($pages);
    }

    public function testGetPagesTotalPagesGreatherThanMaxPages(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(
            SimpleUrlGenerator::class,
            [
              'generatePageUrl' => 'my_url'
            ]
        );
        $s = new Pagination($urlGenerator, $renderer);

        $s->setTotalItems(200)
           ->setCurrentPage(3)
           ->setMaxPages(4);

        $pages = $s->getPages();

        $this->assertIsArray($pages);
        $this->assertCount(6, $pages);
        $this->assertNull($pages[1]->getUrl());
        $this->assertEquals('...', $pages[1]->getNumber());
    }

    public function testGetPagesManyPages(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(
            SimpleUrlGenerator::class,
            [
              'generatePageUrl' => 'my_url'
            ]
        );
        $s = new Pagination($urlGenerator, $renderer);

        $s->setTotalItems(200)
           ->setCurrentPage(3)
           ->setMaxPages(10);

        $pages = $s->getPages();

        $this->assertIsArray($pages);
        $this->assertCount(11, $pages);
        $this->assertEquals('my_url', $pages[1]->getUrl());
    }

    public function testGetPagesCurrentPageAndAdjacentsBig(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(
            SimpleUrlGenerator::class,
            [
              'generatePageUrl' => 'my_url'
            ]
        );
        $s = new Pagination($urlGenerator, $renderer);

        $s->setTotalItems(200)
           ->setCurrentPage(8)
           ->setMaxPages(9)
           ->setItemsPerPage(20);

        $pages = $s->getPages();

        $this->assertIsArray($pages);
        $this->assertCount(10, $pages);
        $this->assertNull($pages[1]->getUrl());
        $this->assertEquals('...', $pages[1]->getNumber());
    }

    public function testGetPagesSlidingEndGreatherOrEqualToTotalPages(): void
    {
        $renderer = $this->getMockInstance(DefaultRenderer::class);
        $urlGenerator = $this->getMockInstance(
            SimpleUrlGenerator::class,
            [
              'generatePageUrl' => 'my_url'
            ]
        );
        $s = new Pagination($urlGenerator, $renderer);

        $s->setTotalItems(200)
           ->setCurrentPage(8)
           ->setMaxPages(7)
           ->setItemsPerPage(20);

        $pages = $s->getPages();

        $this->assertEquals(10, $s->getTotalPages());
        $this->assertIsArray($pages);
        $this->assertCount(7, $pages);
        $this->assertNull($pages[1]->getUrl());
        $this->assertEquals('...', $pages[1]->getNumber());
    }
}
