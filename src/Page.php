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
 *  @file Page.php
 *
 *  The Page information class
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

/**
 * Class Page
 * @package Platine\Pagination
 */
class Page
{

    /**
     * The page number
     * @var int|string
     */
    protected $number;

    /**
     * The page URL
     * @var string|null
     */
    protected ?string $url;

    /**
     * Whether is the current page
     * @var bool
     */
    protected bool $current = false;

    /**
     * Create new instance
     * @param int|string $number
     * @param string|null $url
     * @param bool $current
     */
    public function __construct($number, ?string $url, bool $current = false)
    {
        $this->number = $number;
        $this->url = $url;
        $this->current = $current;
    }

    /**
     * Return the page number
     * @return int|string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Return the page URL
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Whether is the current page
     * @return bool
     */
    public function isCurrent(): bool
    {
        return $this->current;
    }
}
