<?php
namespace Liuxinyang\HyperfAdmin\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;

#[Command(name: "init:data")]
class InitDataCommand extends HyperfCommand
{

    public function handle()
    {
        //文件拷贝
        //1、config文件
        $configArray = [
            'annotations.php',
            'middlewares.php',
            'server.php',
            'session.php',
            'view.php'
        ];
        foreach ($configArray as $file) {
            unlink(BASE_PATH . '/config/autoload/'.$file);
            copy(BASE_PATH . '/vendor/liuxinyang/hyperf-admin/config/'.$file, BASE_PATH . '/config/autoload/'.$file);
        }

        self::copyFolder(BASE_PATH.'/assets', BASE_PATH.'/public/vendor');

        //数据导入

        $this->line('Success!', 'info');
    }

    private static function copyFolder($src, $dest)
    {
        // 如果目标目录不存在，则创建目标目录

        if (!is_dir($dest)) {
            mkdir($dest);
        }

        // 获取原始目录中的文件和目录
        $files = scandir($src);

        // 遍历文件和目录
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                // 如果该文件是一个目录，则递归调用该函数复制子目录
                if (is_dir($src . DIRECTORY_SEPARATOR . $file)) {
                    self::copyFolder($src . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
                } else {
                    // 如果该文件是一个文件，则复制文件到目标目录中
                    copy($src . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
                }
            }
        }
    }
}
