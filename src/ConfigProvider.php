<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Liuxinyang\HyperfAdmin;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [

            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        BASE_PATH . '/vendor/liuxinyang/hyperf-admin/src',
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config of hyperfAdmin.',
                    'source' => __DIR__ . '/../publish/hyperfAdmin.php',
                    'destination' => BASE_PATH . '/config/autoload/hyperfAdmin.php',
                ],
            ],
        ];
    }
}
