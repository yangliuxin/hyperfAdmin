<?php
declare(strict_types=1);

namespace App\Controller\Admin;


use Yangliuxin\Utils\Utils\ServiceConstant;
use Hyperf\Database\Schema\Schema;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;

#[Controller]
class ScaffoldController extends AbstractAdminController
{
    #[RequestMapping('/admin/scaffold', methods: ['GET'])]
    public function index()
    {
        $user = $this->_init();
        if (!$user) {
            $this->session->remove("admin");
            $this->session->clear();
            return $this->response->redirect('/admin/login');
        }
        $tables = DB::select('SHOW TABLES');
        $tableList = [];
        foreach ($tables as $table) {
            $tableKey = 'Tables_in_' . strtolower(\Hyperf\Config\config("databases.default.database"));
            $table = json_decode(json_encode($table), true);
            if (!in_array($table[$tableKey], ['admin_users', 'admin_roles', 'admin_role_permissions', 'admin_role_users', 'admin_menus', 'admin_stats'])) {
                $tableList[] = $table[$tableKey];
            }

        }
        $this->bladeData['tables'] = $tableList;

        return $this->render->render('admin/scaffold/index', $this->bladeData);
    }

    #[RequestMapping('/admin/api/scaffold', methods: ['POST'])]
    public function scaffoldPost()
    {
        $user = $this->_init();
        if (!$user) {
            $this->session->remove("admin");
            $this->session->clear();
            return ServiceConstant::error(ServiceConstant::MSG_TOKEN_ERROR);
        }
        $tableName = $this->request->input("table");
        $modelNameSpace = $this->request->input("model");
        $controllerNameSpace = $this->request->input("controller");
        if (!$tableName || !$modelNameSpace || !$controllerNameSpace) {
            return ServiceConstant::error(ServiceConstant::MSG_PARAM_ERROR);
        }
        //生成代码
        $upperClassName = str_replace(" ", "", ucwords(str_replace("_", " ", $tableName)));
        $lowerClassName = lcfirst($upperClassName);
        $lowerPathName = self::camelCaseToUnderscore($tableName);
        //获取表字段
        $fields = [];
        $columns = Schema::getColumnListing($tableName);
        $tableColumnArray = [];
        foreach ($columns as $column) {
            $tableColumnArray[] = $column;
            $fields[$column] = Schema::getColumnType($tableName, $column);
        }

        $templatePath = realpath(BASE_PATH) . '/resource/templates/';
        if ($this->request->input('build_model')) {
            $modelContent = file_get_contents($templatePath . 'model.vm');
            $modelContent = str_replace("#upperClassName#", $upperClassName, $modelContent);
            $modelContent = str_replace("#tableName#", $tableName, $modelContent);
            $modelContent = str_replace("#tableColumnArray#", json_encode($tableColumnArray, 320), $modelContent);
            file_put_contents(realpath(BASE_PATH) . '/app/Model/' . $upperClassName . '.php', $modelContent);
        }
        //Model

        if ($this->request->input('build_controller')) {
            //controller
            $modelRequest = file_get_contents($templatePath . 'controller_request.vm');
            $controllerContent = file_get_contents($templatePath . 'controller.vm');
            $controllerContent = str_replace("#upperClassName#", $upperClassName, $controllerContent);
            $controllerContent = str_replace("#lowerClassName#", $lowerClassName, $controllerContent);
            $controllerContent = str_replace("#lowerPathName#", $lowerPathName, $controllerContent);
            $modelRequestContent = '';
            foreach ($fields as $key => $field) {
                if (!in_array($key, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    $tempStr = $modelRequest;
                    $tempStr = str_replace("#field#", $key, $tempStr);
                    $tempStr = str_replace("#fieldComment#", $key, $tempStr);
                    $tempStr = str_replace("#lowerPathName#", $lowerPathName, $tempStr);
                    $modelRequestContent .= $tempStr;
                }

            }
            $controllerContent = str_replace("#modelRequest#", $modelRequestContent, $controllerContent);
            file_put_contents(realpath(BASE_PATH) . '/app/Controller/Admin/' . $upperClassName . 'Controller.php', $controllerContent);
        }

        if ($this->request->input('build_view')) {
            if (!file_exists(realpath(BASE_PATH) . '/resource/view/admin/' . $lowerPathName)) {
                mkdir(realpath(BASE_PATH) . '/resource/view/admin/' . $lowerPathName);
            }

            //list
            $listContent = file_get_contents($templatePath . 'list.vm');
            $listContent = str_replace("#tableName#", $tableName, $listContent);
            $listContent = str_replace("#upperClassName#", $upperClassName, $listContent);
            $listContent = str_replace("#lowerClassName#", $lowerClassName, $listContent);
            $listContent = str_replace("#lowerPathName#", $lowerPathName, $listContent);
            $thRowContent = '';
            $thRowTemplate = "<th>#field#</th>";
            foreach ($fields as $key => $field) {
                if (!in_array($key, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    $tempStr = $thRowTemplate;
                    $tempStr = str_replace("#field#", $key, $tempStr);
                    $thRowContent .= $tempStr;
                }
            }
            $listContent = str_replace("#thRowContent#", $thRowContent, $listContent);
            $tdRowContent = '';
            $tdRowTemplate = "<td>#fieldValue#</td>";
            foreach ($fields as $key => $field) {
                if (!in_array($key, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    $tempStr = $tdRowTemplate;
                    $tempStr = str_replace("#fieldValue#", '{{$data[' . "'$key'" . ']}}', $tempStr);
                    $tdRowContent .= $tempStr;
                }
            }
            $listContent = str_replace("#tdRowContent#", $tdRowContent, $listContent);
            file_put_contents(realpath(BASE_PATH) . '/resource/view/admin/' . $lowerPathName . '/list.blade.php', $listContent);

            //edit
            $editContent = file_get_contents($templatePath . 'edit.vm');
            $editContent = str_replace("#tableName#", $tableName, $editContent);
            $editContent = str_replace("#upperClassName#", $upperClassName, $editContent);
            $editContent = str_replace("#lowerClassName#", $lowerClassName, $editContent);
            $editContent = str_replace("#lowerPathName#", $lowerPathName, $editContent);

            $formContent = '';
            $formRowTemplate = file_get_contents($templatePath . 'form_row.vm');;
            foreach ($fields as $key => $field) {
                if (!in_array($key, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    $tempStr = $formRowTemplate;
                    $tempStr = str_replace("#field#", $key, $tempStr);
                    $formContent .= $tempStr;
                }
            }
            $editContent = str_replace("#formListContent#", $formContent, $editContent);
            file_put_contents(realpath(BASE_PATH) . '/resource/view/admin/' . $lowerPathName . '/edit.blade.php', $editContent);
        }

        return ServiceConstant::success();
    }

    private static function camelCaseToUnderscore($string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    private static function toUpperCamelCase($string): string
    {
        return lcfirst(ucwords(str_replace(['-', '_'], ' ', $string)));

    }

}