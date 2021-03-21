<?php

/*
 * This file is part of the League\Fractal package.
 *
 * (c) Phil Sturgeon <me@philsturgeon.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Fractal\Pagination;

use Zend\Paginator\Paginator;

/**
 * A paginator adapter for zendframework/zend-paginator.
 *
 * @author Abdul Malik Ikhsan <samsonasik@gmail.com>
 */
class ZendFrameworkPaginatorAdapter implements PaginatorInterface
{
    /**
     * The paginator instance.
     *
     * @var \Zend\Paginator\Paginator
     */
    protected $paginator;

    /**
     * The route generator.
     *
     * @var callable
     */
    protected $routeGenerator;

    /**
     * Create a new zendframework pagination adapter.
     *
     * @param \Zend\Paginator\Paginator $paginator
     * @param callable                  $routeGenerator
     *
     * @return void
     */
    public function __construct(Paginator $paginator, $routeGenerator)
    {
        $this->paginator = $paginator;
        $this->routeGenerator = $routeGenerator;
    }

    /**
     * Get the current page.
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->paginator->getCurrentPageNumber();
    }

    /**
     * Get the last page.
     *
     * @return int
     */
    public function getLastPage()
    {
        return $this->paginator->count();
    }

    /**
     * Get the total.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->paginator->getTotalItemCount();
    }

    /**
     * Get the count.
     *
     * @return int
     */
    public function getCount()
    {
        return $this->paginator->getCurrentItemCount();
    }

    /**
     * Get the number per page.
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->paginator->getItemCountPerPage();
    }

    /**
     * Get the url for the given page.
     *
     * @param int $page
     *
     * @return string
     */
    public function getUrl($page)
    {
        return call_user_func($this->routeGenerator, $page);
    }

    /**
     * Get the paginator instance.
     *
     * @return \Zend\Paginator\Paginator
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * Get the the route generator.
     *
     * @return callable
     */
    public function getRouteGenerator()
    {
        return $this->routeGenerator;
    }
}
