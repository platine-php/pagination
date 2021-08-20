<?php

declare(strict_types=1);

namespace Platine\Test\Pagination\Renderer;

use Platine\Pagination\Page;
use Platine\Pagination\Pagination;
use Platine\Pagination\Renderer\DefaultRenderer;
use Platine\Dev\PlatineTestCase;

/**
 * DefaultRenderer class tests
 *
 * @group core
 * @group pagination
 */
class DefaultRendererTest extends PlatineTestCase
{

    public function testConstructor(): void
    {
        $s = new DefaultRenderer();
        $this->assertInstanceOf(DefaultRenderer::class, $s);
    }

    public function testRenderEmpty(): void
    {
        $pagination = $this->getMockInstance(
            Pagination::class,
            [
                    'getTotalPages' => 1
                ]
        );
        $s = new DefaultRenderer();

        $this->assertEmpty($s->render($pagination));
    }

    public function testRenderFull(): void
    {
        $page1 = $this->getMockInstance(
            Page::class,
            [
                    'getNumber' => 1,
                    'getUrl' => '/page/1',
                    'isCurrent' => false,
                ]
        );

        $page2 = $this->getMockInstance(
            Page::class,
            [
                    'getNumber' => 2,
                    'getUrl' => '/page/2',
                    'isCurrent' => true,
                ]
        );

        $page3 = $this->getMockInstance(
            Page::class,
            [
                    'getNumber' => '...',
                    'getUrl' => null,
                    'isCurrent' => false,
                ]
        );

        $page4 = $this->getMockInstance(
            Page::class,
            [
                    'getNumber' => 4,
                    'getUrl' => '/page/4',
                    'isCurrent' => false,
                ]
        );

        $pagination = $this->getMockInstance(
            Pagination::class,
            [
                    'getTotalPages' => 3,
                    'hasPreviousPage' => true,
                    'hasNextPage' => true,
                    'getPreviousUrl' => '/page/1',
                    'getNextUrl' => '/page/3',
                    'getPreviousText' => 'Prev',
                    'getNextText' => 'Next',
                    'getPages' => [$page1, $page2, $page3, $page4],
                ]
        );
        $s = new DefaultRenderer();

        $this->assertEquals(
            '<ul class = "pagination"><li><a href = "/page/1">'
                . '&laquo; Prev</a></li><li><a href = "/page/1">1</a></li>'
                . '<li class = "active"><a href = "/page/2">2</a></li>'
                . '<li class = "disabled"><span>...</span></li>'
                . '<li><a href = "/page/4">4</a></li><li>'
                . '<a href = "/page/3">Next &raquo;</a></li></ul>',
            $s->render($pagination)
        );
    }
}
