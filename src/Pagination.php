<?php

/**
 * Platine Pagination
 *
 * Platine Pagination is a lightweight PHP paginator, for generating pagination controls
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2020 Platine Pagination
 * Copyright (c) 2014 Jason Grimes
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 *  @file Pagination.php
 *
 *  The pagination main class
 *
 *  @package    Platine\Pagination
 *  @author Platine Developers Team
 *  @copyright  Copyright (c) 2020
 *  @license    http://opensource.org/licenses/MIT  MIT License
 *  @link   http://www.iacademy.cf
 *  @version 1.0.0
 *  @filesource
 */

declare(strict_types=1);

namespace Platine\Pagination;

use InvalidArgumentException;
use Platine\Pagination\Renderer\DefaultRenderer;
use Platine\Pagination\UrlGenerator\SimpleUrlGenerator;

/**
 * Class Pagination
 * @package Platine\Pagination
 */
class Pagination
{

    /**
     * The URL generator instance
     * @var UrlGeneratorInterface
     */
    protected UrlGeneratorInterface $urlGenerator;

    /**
     * The pagination renderer
     * @var RendererInterface
     */
    protected RendererInterface $renderer;

    /**
     * The pagination total items
     * @var int
     */
    protected int $totalItems = 0;

    /**
     * The pagination number of items per page
     * @var int
     */
    protected int $itemsPerPage = 10;

    /**
     * The pagination current page number
     * @var int
     */
    protected int $currentPage = 1;

    /**
     * The pagination total page after calculation
     * @var int
     */
    protected int $totalPages = 0;

    /**
     * The pagination max page to show
     * @var int
     */
    protected int $maxPages = 10;

    /**
     * The pagination previous text
     * @var string
     */
    protected string $previousText = 'Previous';

    /**
     * The pagination next text
     * @var string
     */
    protected string $nextText = 'Next';

    /**
     * Create new instance
     * @param UrlGeneratorInterface|null $urlGenerator
     * @param RendererInterface|null $renderer
     */
    public function __construct(
        ?UrlGeneratorInterface $urlGenerator = null,
        ?RendererInterface $renderer = null
    ) {
        $this->urlGenerator = $urlGenerator
                               ? $urlGenerator
                               : new SimpleUrlGenerator();

        $this->renderer = $renderer
                            ? $renderer
                            : new DefaultRenderer();

        $this->updateTotalPages();
    }

    /**
     * Whether the pagination has next page
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->totalPages;
    }

    /**
     * Return the next page number
     * @return int|null
     */
    public function getNextPage(): ?int
    {
        if ($this->hasNextPage()) {
            return $this->currentPage + 1;
        }

        return null;
    }

    /**
     * Return the next page URL
     * @return string|null
     */
    public function getNextUrl(): ?string
    {
        if ($this->hasNextPage()) {
            return $this->getPageUrl($this->getNextPage());
        }

        return null;
    }

    /**
     * Whether the pagination has previous page
     * @return bool
     */
    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    /**
     * Return the previous page number
     * @return int|null
     */
    public function getPreviousPage(): ?int
    {
        if ($this->hasPreviousPage()) {
            return $this->currentPage - 1;
        }

        return null;
    }

    /**
     * Return the previous page URL
     * @return string|null
     */
    public function getPreviousUrl(): ?string
    {
        if ($this->hasPreviousPage()) {
            return $this->getPageUrl($this->getPreviousPage());
        }

        return null;
    }

    /**
     * Return the offset so that it can be used
     * in other contexts, for example in SQL query
     * @return int
     */
    public function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }

    /**
     * Return the page URL for the given number
     * @param int $page
     * @return string
     */
    public function getPageUrl(int $page): string
    {
        return $this->urlGenerator->generatePageUrl($page);
    }

    /**
     * Set the URL generator to use
     * @param UrlGeneratorInterface $urlGenerator
     * @return $this
     */
    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator): self
    {
        $this->urlGenerator = $urlGenerator;

        return $this;
    }

    /**
     * Set the renderer to use
     * @param RendererInterface $renderer
     * @return $this
     */
    public function setRenderer(RendererInterface $renderer): self
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * Set total items
     * @param int $totalItems
     * @return $this
     */
    public function setTotalItems(int $totalItems): self
    {
        $this->totalItems = $totalItems;

        $this->updateTotalPages();

        return $this;
    }

    /**
     * Set number of items to show per page
     * @param int $itemsPerPage
     * @return $this
     */
    public function setItemsPerPage(int $itemsPerPage): self
    {
        $this->itemsPerPage = $itemsPerPage;

        $this->updateTotalPages();

        return $this;
    }

    /**
     * Set current page number
     * @param int $currentPage
     * @return $this
     */
    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
     * Set max pages to show int the links
     * @param int $maxPages
     * @return $this
     */
    public function setMaxPages(int $maxPages): self
    {
        if ($maxPages < 3) {
            throw new InvalidArgumentException(sprintf(
                'Max page to show can not be less than 3, %d given',
                $maxPages
            ));
        }

        $this->maxPages = $maxPages;

        return $this;
    }

    /**
     * Set previous text
     * @param string $previousText
     * @return $this
     */
    public function setPreviousText(string $previousText): self
    {
        $this->previousText = $previousText;

        return $this;
    }

    /**
     * Set next text
     * @param string $nextText
     * @return $this
     */
    public function setNextText(string $nextText): self
    {
        $this->nextText = $nextText;

        return $this;
    }

    /**
     * Return the URL generator instance
     * @return UrlGeneratorInterface
     */
    public function getUrlGenerator(): UrlGeneratorInterface
    {
        return $this->urlGenerator;
    }

    /**
     * Return the renderer instance
     * @return RendererInterface
     */
    public function getRenderer(): RendererInterface
    {
        return $this->renderer;
    }

    /**
     * Return the previous text
     * @return string
     */
    public function getPreviousText(): string
    {
        return $this->previousText;
    }

    /**
     * Return the next text
     * @return string
     */
    public function getNextText(): string
    {
        return $this->nextText;
    }

    /**
     * Return the total items
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * Return the number of items per page
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * Return the current page number
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Return the total number of pages
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * Return the max pages to show
     * @return int
     */
    public function getMaxPages(): int
    {
        return $this->maxPages;
    }

    /**
     * Return the pages links data
     *
     * @return array<Page>
     */
    public function getPages(): array
    {
        $pages = [];

        if ($this->totalPages <= 1) {
            return [];
        }

        if ($this->totalPages <= $this->maxPages) {
            for ($i = 1; $i <= $this->totalPages; $i++) {
                $pages[] = $this->createPage($i, $i === $this->currentPage);
            }
        } else {
            // Determine the sliding range, centered around the current page.
            $numAdjacents = (int) floor(($this->maxPages - 3) / 2);
            $slidingStart = -1;

            if ($this->currentPage + $numAdjacents > $this->totalPages) {
                $slidingStart = $this->totalPages - $this->maxPages + 2;
            } else {
                $slidingStart = $this->currentPage - $numAdjacents;
            }

            if ($slidingStart < 2) {
                $slidingStart = 2;
            }

            $slidingEnd = $slidingStart + $this->maxPages - 3;

            if ($slidingEnd >= $this->totalPages) {
                $slidingEnd = $this->totalPages - 1;
            }

            // Build the list of pages.
            $pages[] = $this->createPage(1, 1 === $this->currentPage);

            if ($slidingStart > 2) {
                $pages[] = $this->createPageEllipsis();
            }

            for ($i = $slidingStart; $i <= $slidingEnd; $i++) {
                $pages[] = $this->createPage($i, $i === $this->currentPage);
            }

            if ($slidingEnd < $this->totalPages - 1) {
                $pages[] = $this->createPageEllipsis();
            }

            $pages[] = $this->createPage(
                $this->totalPages,
                $this->totalPages === $this->currentPage
            );
        }

        return $pages;
    }

    /**
     * Render the pagination links
     * @return string
     */
    public function render(): string
    {
        return $this->renderer->render($this);
    }

    /**
     * The string representation of pagination
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Create page data
     * @param int $page
     * @param bool $isCurrent
     * @return Page
     */
    protected function createPage(int $page, bool $isCurrent = false): Page
    {
        return new Page(
            $page,
            $this->getPageUrl($page),
            $isCurrent,
        );
    }

    /**
     * Create page ellipsis data
     * @return Page
     */
    protected function createPageEllipsis(): Page
    {
        return new Page(
            '...',
            null,
            false,
        );
    }

    /**
     * Update the total pages information's
     * @return void
     */
    protected function updateTotalPages(): void
    {
        $this->totalPages = ($this->itemsPerPage <= 0)
                            ? 0
                            : (int) ceil($this->totalItems / $this->itemsPerPage);
    }
}
