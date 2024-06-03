<?php
declare(strict_types=1);

namespace Liuxinyang\HyperfAdmin\Controller\Admin;

use Hyperf\HttpMessage\Cookie\Cookie;
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
        if(!$user){
            $this->session->remove("admin");
            $this->session->clear();
            return $this->response->withCookie(new Cookie('admin', ''))->redirect('/admin/login');
        }
        if(!$this->checkPermission($user['id'],'scaffold')){
            return $this->render->render('/admin/noauth', $this->bladeData);
        }
        $tables = Db::select('SHOW TABLES');
        $tableList = [];
        foreach ($tables as $table) {
            $table = json_decode(json_encode($table), true);
            $table = array_values($table);
            if (!in_array($table[0], ['admin_users', 'admin_roles', 'admin_role_permissions', 'admin_role_users', 'admin_menus', 'admin_stats'])) {
                $tableList[] = $table[0];
            }
        }
        $this->bladeData['tables'] = $tableList;

        return $this->render->render('admin/scaffold/index', $this->bladeData);
    }

    #[RequestMapping('/admin/api/scaffold/table', methods: ['POST'])]
    public function scaffoldTablePost()
    {
        $user = $this->_init();
        if(!$user){
            $this->session->remove("admin");
            $this->session->clear();
            return $this->response->withCookie(new Cookie('admin', ''))->redirect('/admin/login');
        }
        if(!$this->checkPermission($user['id'],'scaffold')){
            return ServiceConstant::error('no permission');
        }
        $tableName = $this->request->input("table");
        $columns = Schema::getColumnListing($tableName);
        $columns = array_filter($columns, function ($val) {
            return !in_array($val, ['id', 'created_at', 'updated_at']);
        });
        return ServiceConstant::success(array_values($columns));
    }

    #[RequestMapping('/admin/api/scaffold', methods: ['POST'])]
    public function scaffoldPost()
    {
        $user = $this->_init();
        if(!$user){
            $this->session->remove("admin");
            $this->session->clear();
            return ServiceConstant::error(ServiceConstant::MSG_TOKEN_ERROR);
        }
        if(!$this->checkPermission($user['id'],'scaffold')){
            return ServiceConstant::error('no permission');
        }
        $tableName = $this->request->input("table");
        $modelNameSpace = $this->request->input("model");
        $controllerNameSpace = $this->request->input("controller");
        $moduleName = $this->request->input("module_name");
        $fieldType = $this->request->input("field_type");
        $fieldComment = $this->request->input("field_comment");
        if (!$tableName || !$modelNameSpace || !$controllerNameSpace || !$moduleName || !$fieldType || !$fieldComment) {
            return ServiceConstant::error(ServiceConstant::MSG_PARAM_ERROR);
        }

        $fieldType = json_decode($fieldType, true);
        $fieldComment = json_decode($fieldComment, true);
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

        //Model
        $templatePath = realpath(BASE_PATH) . '/vendor/liuxinyang/hyperf-admin/resources/templates/';
        if ($this->request->input('build_model')) {
            $modelContent = file_get_contents($templatePath . 'model.vm');
            $modelContent = str_replace("#upperClassName#", $upperClassName, $modelContent);
            $modelContent = str_replace("#tableName#", $tableName, $modelContent);
            $modelContent = str_replace("#tableColumnArray#", json_encode($tableColumnArray, 320), $modelContent);
            $defaultAttributes = '';
            foreach ($fieldType as $field => $type){
                if(in_array($type, ['number', 'select', 'switch'])){
                    $defaultAttributes .= "'$field' => 0,";
                }
            }
            $modelContent = str_replace("#defaultAttributes#", $defaultAttributes, $modelContent);
            
            file_put_contents(realpath(BASE_PATH) . '/app/Model/' . $upperClassName . '.php', $modelContent);
        }

        //controller
        if ($this->request->input('build_controller')) {
            
            $modelRequest = file_get_contents($templatePath . 'controller_request.vm');
            $modelRequestPic = file_get_contents($templatePath . 'controller_request_pic.vm');
            $modelRequestAlbum = file_get_contents($templatePath . 'controller_request_album.vm');
            $controllerContent = file_get_contents($templatePath . 'controller.vm');
            $controllerContent = str_replace("#upperClassName#", $upperClassName, $controllerContent);
            $controllerContent = str_replace("#lowerClassName#", $lowerClassName, $controllerContent);
            $controllerContent = str_replace("#lowerPathName#", $lowerPathName, $controllerContent);
            $modelRequestContent = '';
            foreach ($fieldType as $field => $type) {
                if (!in_array($field, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    if($type == 'pic'){
                        $tempStr = $modelRequestPic;
                        $tempStr = str_replace("#field#", $field, $tempStr);
                        $modelRequestContent .= $tempStr;
                    } elseif ($type == 'album'){
                        $tempStr = $modelRequestAlbum;
                        $tempStr = str_replace("#field#", $field, $tempStr);
                        $modelRequestContent .= $tempStr;
                    } else {
                        $tempStr = $modelRequest;
                        $tempStr = str_replace("#field#", $field, $tempStr);
                        $tempStr = str_replace("#fieldComment#", $fieldComment[$field], $tempStr);
                        $tempStr = str_replace("#lowerPathName#", $lowerPathName, $tempStr);
                        $modelRequestContent .= $tempStr;
                    } 
                    
                }

            }
            $controllerContent = str_replace("#modelRequest#", $modelRequestContent, $controllerContent);
            file_put_contents(realpath(BASE_PATH) . '/app/Controller/Admin/' . $upperClassName . 'Controller.php', $controllerContent);
        }
        
        //view
        if ($this->request->input('build_view')) {
            if (!file_exists(realpath(BASE_PATH) . '/resources/view/admin/' . $lowerPathName)) {
                mkdir(realpath(BASE_PATH) . '/resources/view/admin/' . $lowerPathName);
            }

            //list
            $listContent = file_get_contents($templatePath . 'list.vm');
            $listContent = str_replace("#tableNameComment#", $moduleName, $listContent);
            $listContent = str_replace("#tableName#", $tableName, $listContent);
            $listContent = str_replace("#upperClassName#", $upperClassName, $listContent);
            $listContent = str_replace("#lowerClassName#", $lowerClassName, $listContent);
            $listContent = str_replace("#lowerPathName#", $lowerPathName, $listContent);
            $thRowContent = '';
            $thRowTemplate = "<th>#field#</th>";
            $columnCount = 3;
            foreach ($fieldComment as $field => $comment) {
                if (!in_array($field, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    $tempStr = $thRowTemplate;
                    $tempStr = str_replace("#field#", $comment, $tempStr);
                    $thRowContent .= $tempStr;
                    $columnCount++;
                }
            }
            $listContent = str_replace("#thRowContent#", $thRowContent, $listContent);
            $tdRowContent = '';
            $tdRowTemplate = "<td>#fieldValue#</td>";
            $tdRowTemplatePic = "<td><img src='#fieldValue#' width='50'></td>";
            $tdRowTemplateAlbum = '<td> @foreach(explode(",", $data["#field#"]) as $pic) <img src="{{$pic}}" height="50">&nbsp; @endforeach</td>';
            foreach ($fieldType as $field => $type) {
                if (!in_array($field, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    $tempStr = $tdRowTemplate;
                    if($type == 'pic'){
                        $tempStr = str_replace("#fieldValue#", '{{$data[' . "'$field'" . ']}}', $tdRowTemplatePic);
                    } elseif ($type == 'album'){
                        $tempStr = str_replace("#field#", $field, $tdRowTemplateAlbum);
                    } else {
                        $tempStr = str_replace("#fieldValue#", '{{$data[' . "'$field'" . ']}}', $tdRowTemplate);
                    }
                    
                    $tdRowContent .= $tempStr;
                }
            }
            $listContent = str_replace("#columnCount#", $columnCount, $listContent);
            $listContent = str_replace("#tdRowContent#", $tdRowContent, $listContent);
            file_put_contents(realpath(BASE_PATH) . '/resources/view/admin/' . $lowerPathName . '/list.blade.php', $listContent);

            //edit
            $editContent = file_get_contents($templatePath . 'edit.vm');
            $editContent = str_replace("#tableNameComment#", $moduleName, $editContent);
            $editContent = str_replace("#tableName#", $tableName, $editContent);
            $editContent = str_replace("#upperClassName#", $upperClassName, $editContent);
            $editContent = str_replace("#lowerClassName#", $lowerClassName, $editContent);
            $editContent = str_replace("#lowerPathName#", $lowerPathName, $editContent);

            $formContent = '';
            $formRowTemplate = file_get_contents($templatePath . 'form_row.vm');
            $formRowTemplatePic = file_get_contents($templatePath . 'form_row_pic.vm');
            $formRowTemplateAlbum = file_get_contents($templatePath . 'form_row_album.vm');
            $formRowTemplateSelect = file_get_contents($templatePath . 'form_row_select.vm');
            $formRowTemplateSwitch = file_get_contents($templatePath . 'form_row_switch.vm');
            $formRowTemplateTextarea = file_get_contents($templatePath . 'form_row_textarea.vm');
            $formRowTemplateNumber = file_get_contents($templatePath . 'form_row_number.vm');

            $formJsTemplatePic = file_get_contents($templatePath . 'form_js_pic.vm');
            $formJsTemplateAlbum = file_get_contents($templatePath . 'form_js_album.vm');
            $formJsTemplateSelect = file_get_contents($templatePath . 'form_js_select.vm');
            $formJsTemplateSwitch = file_get_contents($templatePath . 'form_js_switch.vm');
            $formJsTemplateNumber = file_get_contents($templatePath . 'form_js_number.vm');
            
            $formCssContent = '';
            $formJsContent = '';
            $formCssArray =[];
            $formJsArray =[];
            $formScriptContent = '';
            foreach ($fieldType as $field => $type) {
                if (!in_array($field, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    if($type == 'pic'){
                        $formCssArray[] = '<link rel="stylesheet" href="/vendor/bootstrap-fileinput/css/fileinput.min.css">';
                        $formJsArray[] = '<script src="/vendor/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js"></script>';
                        $formJsArray[] = '<script src="/vendor/bootstrap-fileinput/js/fileinput.min.js"></script>';
                        
                        $tempStr = str_replace("#fieldComment#", $fieldComment[$field], $formRowTemplatePic);
                        $tempStr = str_replace("#field#", $field, $tempStr);
                        $tempScriptStr = str_replace("#field#", $field, $formJsTemplatePic);
                        $tempScriptStr = str_replace("#fieldComment#", $fieldComment[$field], $tempScriptStr);
                        $formScriptContent .= $tempScriptStr;
                        $formContent .= $tempStr;
                    } elseif($type == 'album'){
                        $formCssArray[] = '<link rel="stylesheet" href="/vendor/bootstrap-fileinput/css/fileinput.min.css">';
                        $formJsArray[] = '<script src="/vendor/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js"></script>';
                        $formJsArray[] = '<script src="/vendor/bootstrap-fileinput/js/fileinput.min.js"></script>';
                        
                        $tempStr = str_replace("#fieldComment#", $fieldComment[$field], $formRowTemplateAlbum);
                        $tempStr = str_replace("#field#", $field, $tempStr);
                        $tempScriptStr = str_replace("#field#", $field, $formJsTemplateAlbum);
                        $tempScriptStr = str_replace("#fieldComment#", $fieldComment[$field], $tempScriptStr);
                        $formScriptContent .= $tempScriptStr;
                        $formContent .= $tempStr;
                    }  elseif($type == 'select'){
                        $formCssArray[] = '<link rel="stylesheet" href="/vendor/AdminLTE/plugins/select2/select2.min.css">">';
                        $formCssArray[] = '<link rel="stylesheet" href="/vendor/AdminLTE/dist/css/AdminLTE.min.css">">';
                        $formJsArray[] = '<script src="/vendor/AdminLTE/plugins/select2/select2.full.min.js"></script>';
                        
                        $tempStr = str_replace("#fieldComment#", $fieldComment[$field], $formRowTemplateSelect);
                        $tempStr = str_replace("#field#", $field, $tempStr);
                        $tempScriptStr = str_replace("#field#", $field, $formJsTemplateSelect);
                        $tempScriptStr = str_replace("#fieldComment#", $fieldComment[$field], $tempScriptStr);
                        $formScriptContent .= $tempScriptStr;
                        $formContent .= $tempStr;
                    }  elseif($type == 'switch'){
                        $formCssArray[] = '<link rel="stylesheet" href="/vendor/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css">';
                        $formJsArray[] = '<script src="/vendor/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>';
                        
                        $tempStr = str_replace("#fieldComment#", $fieldComment[$field], $formRowTemplateSwitch);
                        $tempStr = str_replace("#field#", $field, $tempStr);
                        $tempScriptStr = str_replace("#field#", $field, $formJsTemplateSwitch);
                        $tempScriptStr = str_replace("#fieldComment#", $fieldComment[$field], $tempScriptStr);
                        $formScriptContent .= $tempScriptStr;
                        $formContent .= $tempStr;
                    }  elseif($type == 'textarea'){
                        $tempStr = str_replace("#fieldComment#", $fieldComment[$field], $formRowTemplateTextarea);
                        $tempStr = str_replace("#field#", $field, $tempStr);
                        $formContent .= $tempStr;
                    }  elseif($type == 'number'){
                        $formJsArray[] = '<script src="/vendor/number-input/bootstrap-number-input.js"></script>';
                        
                        $tempStr = str_replace("#fieldComment#", $fieldComment[$field], $formRowTemplateNumber);
                        $tempStr = str_replace("#field#", $field, $tempStr);
                        $tempScriptStr = str_replace("#field#", $field, $formJsTemplateNumber);
                        $tempScriptStr = str_replace("#fieldComment#", $fieldComment[$field], $tempScriptStr);
                        $formScriptContent .= $tempScriptStr;
                        $formContent .= $tempStr;
                    } else {
                        $tempStr = str_replace("#fieldComment#", $fieldComment[$field], $formRowTemplate);
                        $tempStr = str_replace("#field#", $field, $tempStr);
                        $formContent .= $tempStr;
                    }
                    
                }
            }
            $formCssContent = join("\n", array_unique($formCssArray));
            $formJsContent = join("\n", array_unique($formJsArray));
            $editContent = str_replace("#formCssContent#", $formCssContent, $editContent);
            $editContent = str_replace("#formJsContent#", $formJsContent, $editContent);
            $editContent = str_replace("#formScriptContent#", $formScriptContent, $editContent);
            $editContent = str_replace("#formListContent#", $formContent, $editContent);
            file_put_contents(realpath(BASE_PATH) . '/resources/view/admin/' . $lowerPathName . '/edit.blade.php', $editContent);
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