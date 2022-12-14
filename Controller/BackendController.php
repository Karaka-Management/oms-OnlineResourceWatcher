<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\OnlineResourceWatcher
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\OnlineResourceWatcher\Controller;

use Modules\OnlineResourceWatcher\Models\ResourceMapper;
use phpOMS\Contract\RenderableInterface;
use phpOMS\DataStorage\Database\Query\OrderType;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;
use Web\Backend\Views\TableView;

/**
 * OnlineResourceWatcher controller class.
 *
 * @package Modules\OnlineResourceWatcher
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewResourceList(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/OnlineResourceWatcher/Theme/Backend/resource-list');

        /* Table functionality */

        $searchFieldData = $request->getLike('.*\-p\-.*');
        $searchField     = [];
        foreach ($searchFieldData as $key => $data) {
            if ($data === '1') {
                $split  = \explode('-', $key);
                $member =  \end($split);

                $searchField[] = $member;
            }
        }

        $filterFieldData = $request->getLike('.*\-f\-.*?\-t');
        $filterField     = [];
        foreach ($filterFieldData as $key => $type) {
            $split = \explode('-', $key);
            \end($split);

            $member = \prev($split);

            if (!empty($request->getData('organizationUserList-f-' . $member . '-f1'))) {
                $filterField[$member] = [
                    'type'   => $type,
                    'value1' => $request->getData('organizationUserList-f-' . $member . '-f1'),
                    'logic1' => $request->getData('organizationUserList-f-' . $member . '-o1'),
                    'value2' => $request->getData('organizationUserList-f-' . $member . '-f2'),
                    'logic2' => $request->getData('organizationUserList-f-' . $member . '-o2'),
                ];
            }
        }

        $pageLimit = 25;
        $view->addData('pageLimit', $pageLimit);

        $mapper = ResourceMapper::getAll()->with('createdBy');
        $list   = ResourceMapper::find(
            search: $request->getData('search'),
            mapper: $mapper,
            id: (int) ($request->getData('id') ?? 0),
            secondaryId: (string) ($request->getData('subid') ?? ''),
            type: $request->getData('pType'),
            pageLimit: empty((int) ($request->getData('limit') ?? 0)) ? 100 : ((int) $request->getData('limit')),
            sortBy: $request->getData('sort_by') ?? '',
            sortOrder: $request->getData('sort_order') ?? OrderType::DESC,
            searchFields: $searchField,
            filters: $filterField
        );

        $view->setData('resources', $list['data']);

        $tableView         = new TableView($this->app->l11nManager, $request, $response);
        $tableView->module = 'OnlineResourceWatcher';
        $tableView->theme  = 'Backend';
        $tableView->setTitleTemplate('/Web/Backend/Themes/table-title');
        $tableView->setColumnHeaderElementTemplate('/Web/Backend/Themes/header-element-table');
        $tableView->setFilterTemplate('/Web/Backend/Themes/popup-filter-table');
        $tableView->setSortTemplate('/Web/Backend/Themes/sort-table');
        $tableView->setData('hasPrevious', $list['hasPrevious']);
        $tableView->setData('hasNext', $list['hasNext']);

        $view->addData('tableView', $tableView);

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewResource(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/OnlineResourceWatcher/Theme/Backend/resource-single');

        $resource = ResourceMapper::get()
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $view->setData('resource', $resource);

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewResourceCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewReportList(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewReport(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        return $view;
    }
}
