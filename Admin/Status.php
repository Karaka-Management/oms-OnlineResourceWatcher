<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\OnlineResourceWatcher\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\OnlineResourceWatcher\Admin;

use phpOMS\Module\StatusAbstract;

/**
 * Status class.
 *
 * @package Modules\OnlineResourceWatcher\Admin
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Status extends StatusAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__;
}