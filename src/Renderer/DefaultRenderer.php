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
 *  @file DefaultRenderer.php
 *
 *  The pagination default renderer class
 *
 *  @package    Platine\Pagination\Renderer
 *  @author Platine Developers Team
 *  @copyright  Copyright (c) 2020
 *  @license    http://opensource.org/licenses/MIT  MIT License
 *  @link   https://www.platine-php.com
 *  @version 1.0.0
 *  @filesource
 */

declare(strict_types=1);

namespace Platine\Pagination\Renderer;

use Platine\Pagination\Page;
use Platine\Pagination\Pagination;
use Platine\Pagination\RendererInterface;

/**
 * @class DefaultRenderer
 * @package Platine\Pagination\Renderer
 */
class DefaultRenderer implements RendererInterface
{
    /**
     * {@inheritdoc}
     */
    public function render(Pagination $pagination): string
    {
        if ($pagination->getTotalPages() <= 1) {
            return '';
        }

        $html = '<ul class = "pagination">';

        if ($pagination->hasPreviousPage()) {
            $html .= sprintf(
                '<li><a href = "%s">&laquo; %s</a></li>',
                $pagination->getPreviousUrl(),
                $pagination->getPreviousText()
            );
        }

        /** @var Page[] $pages */
        $pages = $pagination->getPages();

        foreach ($pages as $page) {
            if ($page->getUrl() !== null) {
                $html .= sprintf(
                    '<li%s><a href = "%s">%d</a></li>',
                    $page->isCurrent() ? ' class = "active"' : '',
                    $page->getUrl(),
                    $page->getNumber()
                );
            } else {
                $html .= sprintf(
                    '<li class = "disabled"><span>%s</span></li>',
                    $page->getNumber()
                );
            }
        }

        if ($pagination->hasNextPage()) {
            $html .= sprintf(
                '<li><a href = "%s">%s &raquo;</a></li>',
                $pagination->getNextUrl(),
                $pagination->getNextText()
            );
        }

        $html .= '</ul>';

        return $html;
    }
}
