<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\OnlineResourceWatcher\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\OnlineResourceWatcher\Models;

/**
 * Inform class.
 *
 * @package Modules\OnlineResourceWatcher\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class InformBlacklist implements \JsonSerializable
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    public string $email = '';

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'    => $this->id,
            'email' => $this->email,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
