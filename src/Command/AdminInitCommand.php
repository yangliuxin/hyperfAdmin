<?php

namespace Liuxinyang\HyperfAdmin\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;
use Hyperf\DbConnection\Db;

#[Command(name: "admin:init")]
class AdminInitCommand extends HyperfCommand
{

    public function handle()
    {
        //文件拷贝
        //1、config文件
        if (!file_exists(BASE_PATH . '/public/vendor/.install')) {
            $configArray = [
                'middlewares.php',
                'server.php',
                'session.php',
                'view.php'
            ];
            foreach ($configArray as $file) {
                if(file_exists(BASE_PATH . '/config/autoload/' . $file)){
                    unlink(BASE_PATH . '/config/autoload/' . $file);
                }
                copy(BASE_PATH . '/vendor/liuxinyang/hyperf-admin/config/' . $file, BASE_PATH . '/config/autoload/' . $file);
            }
            if (!is_dir(BASE_PATH . '/app/Controller/Admin')) {
                mkdir(BASE_PATH . '/app/Controller/Admin');
            }
            if (!is_dir(BASE_PATH . '/public/uploads')) {
                mkdir(BASE_PATH . '/public/uploads', 0777, true);
            }
            if (!is_dir(BASE_PATH . '/public/uploads/images')) {
                mkdir(BASE_PATH . '/public/uploads/images', 0777, true);
            }
            if (!is_dir(BASE_PATH . '/resources')) {
                mkdir(BASE_PATH . '/resources');
            }
            if (!is_dir(BASE_PATH . '/resources/view')) {
                mkdir(BASE_PATH . '/resources/view');
            }
            if (!is_dir(BASE_PATH . '/resources/view/admin')) {
                mkdir(BASE_PATH . '/resources/view/admin');
            }
            self::copyFolder(BASE_PATH . '/vendor/liuxinyang/hyperf-admin/assets', BASE_PATH . '/public/vendor');

            //数据导入
            Schema::dropIfExists('admin_menus');
            Schema::dropIfExists('admin_roles');
            Schema::dropIfExists('admin_users');
            Schema::dropIfExists('admin_role_permissions');
            Schema::dropIfExists('admin_role_users');
            Schema::dropIfExists('admin_stats');
            Schema::create('admin_menus', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('type')->default(0);
                $table->integer('parent_id')->default(0);
                $table->string('title', 50)->default('');
                $table->string('slug', 50)->default('');
                $table->string('icon', 50)->default('');
                $table->string('uri', 50)->default('');
                $table->integer('sort')->default(0);
                $table->timestamps();
                $table->engine = 'InnoDB';
                // 指定数据表的默认字符集
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
            });

            Schema::create('admin_roles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 50)->default('');
                $table->string('slug', 50)->default('');
                $table->timestamps();
                $table->engine = 'InnoDB';
                // 指定数据表的默认字符集
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
            });

            Schema::create('admin_users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username', 120)->default('');
                $table->string('password', 80)->default('');
                $table->string('salt', 64)->default('');
                $table->string('name', 50)->default('');
                $table->string('avatar', 191)->default('');
                $table->timestamps();
                $table->engine = 'InnoDB';
                // 指定数据表的默认字符集
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
            });

            Schema::create('admin_stats', function (Blueprint $table) {
                $table->increments('id');
                $table->string('uri', 50)->default('');
                $table->string('ip', 50)->default('');
                $table->string('province', 50)->default('');
                $table->string('city', 50)->default('');
                $table->timestamps();
                $table->engine = 'InnoDB';
                // 指定数据表的默认字符集
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
            });

            Schema::create('admin_role_permissions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('role_id')->default(0);
                $table->integer('permission_id')->default(0);
                $table->timestamps();
                $table->engine = 'InnoDB';
                // 指定数据表的默认字符集
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
            });

            Schema::create('admin_role_users', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('role_id')->default(0);
                $table->integer('user_id')->default(0);
                $table->timestamps();
                $table->engine = 'InnoDB';
                // 指定数据表的默认字符集
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
            });

            Db::insert("INSERT INTO admin_menus (`id`, `type`, `parent_id`, `title`, `slug`, `icon`, `uri`, `sort`, `created_at`, `updated_at`) VALUES (?, ?,?,?,?,?,?,?,?,?)", [1, 1, 0, '首页', 'home', 'fa-dashboard', '/', 1, '2024-04-17 12:23:01', '2024-04-26 12:56:50']);
            Db::insert("INSERT INTO admin_menus (`id`, `type`, `parent_id`, `title`, `slug`, `icon`, `uri`, `sort`, `created_at`, `updated_at`) VALUES (?, ?,?,?,?,?,?,?,?,?)", [2, 1, 0, '系统设置', 'system', 'fa-tasks', '', 3, '2023-05-21 16:35:31', '2023-05-21 16:35:31']);
            Db::insert("INSERT INTO admin_menus (`id`, `type`, `parent_id`, `title`, `slug`, `icon`, `uri`, `sort`, `created_at`, `updated_at`) VALUES (?, ?,?,?,?,?,?,?,?,?)", [3, 1, 2, '用户管理', 'user', 'fa-user', 'users', 4, '2024-04-16 20:02:22', '2024-04-16 20:02:22']);
            Db::insert("INSERT INTO admin_menus (`id`, `type`, `parent_id`, `title`, `slug`, `icon`, `uri`, `sort`, `created_at`, `updated_at`) VALUES (?, ?,?,?,?,?,?,?,?,?)", [4, 1, 2, '角色管理', 'role', 'fa-user-md', 'roles', 3, '2024-04-16 19:43:50', '2024-04-16 19:43:50']);
            Db::insert("INSERT INTO admin_menus (`id`, `type`, `parent_id`, `title`, `slug`, `icon`, `uri`, `sort`, `created_at`, `updated_at`) VALUES (?, ?,?,?,?,?,?,?,?,?)", [5, 1, 2, '菜单模块', 'menu', 'fa-bars', 'menu', 1, '2024-04-17 12:22:12', '2024-04-17 12:22:12']);
            Db::insert("INSERT INTO admin_menus (`id`, `type`, `parent_id`, `title`, `slug`, `icon`, `uri`, `sort`, `created_at`, `updated_at`) VALUES (?, ?,?,?,?,?,?,?,?,?)", [6, 1, 0, '系统工具', 'system', 'fa-gears', '#', 100, '2024-04-27 10:38:36', '2024-04-27 10:38:36']);
            Db::insert("INSERT INTO admin_menus (`id`, `type`, `parent_id`, `title`, `slug`, `icon`, `uri`, `sort`, `created_at`, `updated_at`) VALUES (?, ?,?,?,?,?,?,?,?,?)", [7, 1, 6, '脚手架', 'scaffold', 'fa-keyboard-o', 'scaffold', 1, '2024-04-27 10:39:40', '2024-04-27 10:39:40']);


            Db::insert("INSERT INTO admin_role_users (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES (?, ?,?,?,?)", [1, 1, 1, '2024-04-25 08:38:41', '2024-04-25 08:38:41']);
            Db::insert("INSERT INTO admin_roles (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (?, ?,?,?,?)", [1,'超级管理员','admin','2024-04-19 13:24:43','2024-04-19 13:24:43']);
            Db::insert("INSERT INTO admin_users (`id`, `username`, `password`, `salt`, `name`, `avatar`, `created_at`, `updated_at`) VALUES (?, ?,?,?,?,?,?,?)", [1,'admin','e7cebef0d9206b446823f7ce09d8cb02','lhI5TZdZ','超级管理员','/uploads/images/5lICa69wBU.png','2023-05-19 09:17:43','2024-04-28 11:54:10']);


        }

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
