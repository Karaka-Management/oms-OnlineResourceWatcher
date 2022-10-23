<?php
declare(strict_types=1);

namespace Models;

use phpOMS\Config\OptionsTrait;
use phpOMS\Config\SettingsInterface;
use phpOMS\DataStorage\Cache\CachePool;

final class CoreSettings implements SettingsInterface
{
    use OptionsTrait;

    protected ?CachePool $cache = null;

    public function get(
        mixed $ids = null,
        string|array $names = null,
        int $app = null,
        string $module = null,
        int $group = null,
        int $account = null
    ) : mixed
    {
        $options = [];

        // get by ids
        if ($ids !== null) {
            if (!\is_array($ids)) {
                $ids = [$ids];
            }

            foreach ($ids as $i => $id) {
                if ($this->exists($id)) {
                    $options[$id] = $this->getOption($id);
                    unset($ids[$i]);
                }
            }
        }

        // get by names
        if ($names !== null) {
            if (!\is_array($names)) {
                $names = [$names];
            }

            foreach ($names as $i => $name) {
                $key = ($name ?? '')
                    . ':' . ($app ?? '')
                    . ':' . ($module ?? '')
                    . ':' . ($group ?? '')
                    . ':' . ($account ?? '');

                $key = \trim($key, ':');

                if ($this->exists($key)) {
                    $options[$key] = $this->getOption($key);
                    unset($names[$i]);
                }
            }
        }

        // all from cache
        if (empty($ids) && empty($names)) {
            return \count($options) > 1 ? $options : \reset($options);
        }

        /** @var \Model\Setting[] $dbOptions */
        $dbOptions = SettingMapper::getSettings([
            'ids'     => $ids,
            'names'   => $names,
            'app'     => $app,
            'module'  => $module,
            'group'   => $group,
            'account' => $account,
        ]);

        // remaining from storage
        try {
            foreach ($dbOptions as $option) {
                $key = ($option->name)
                    . ':' . ($option->app ?? '')
                    . ':' . ($option->module ?? '')
                    . ':' . ($option->group ?? '')
                    . ':' . ($option->account ?? '');

                $key = \trim($key, ':');

                $this->setOption($key, $option, true);

                $options[$key] = $option;
            }
        } catch (\Throwable $e) {
            throw $e; // @codeCoverageIgnore
        }

        return \count($options) > 1 ? $options : \reset($options);
    }

    public function set(array $options, bool $store = false) : void
    {
        /** @var array $option */
        foreach ($options as $option) {
            $key = ($option['name'] ?? '')
                . ':' . ($option['app'] ?? '')
                . ':' . ($option['module'] ?? '')
                . ':' . ($option['group'] ?? '')
                . ':' . ($option['account'] ?? '');

            $key = \trim($key, ':');

            $setting = new Setting();
            $setting->with(
                $option['id'] ?? 0,
                $option['name'] ?? '',
                $option['content'] ?? '',
                $option['pattern'] ?? '',
                $option['app'] ?? null,
                $option['module'] ?? null,
                $option['group'] ?? null,
                $option['account'] ?? null,
            );

            $this->setOption($key, $setting, true);

            if ($store) {
                SettingMapper::saveSetting($setting);
            }
        }
    }

    public function save(array $options = []) : void
    {
        $options = empty($options) ? $this->options : $options;

        foreach ($options as $option) {
            if (\is_array($option)) {
                $setting = new Setting();
                $setting->with(
                    $option['id'] ?? 0,
                    $option['name'] ?? '',
                    $option['content'] ?? '',
                    $option['pattern'] ?? '',
                    $option['app'] ?? null,
                    $option['module'] ?? null,
                    $option['group'] ?? null,
                    $option['account'] ?? null,
                );

                $option = $setting;
            }

            SettingMapper::saveSetting($option);
        }
    }

    public function create(array $options = []) : void
    {
        $setting = new Setting();
        foreach ($options as $column => $option) {
            $setting->{$column} = $option;
        }

        SettingMapper::create()->execute($setting);
    }
}