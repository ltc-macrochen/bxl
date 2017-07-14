<?php
/**
 * BootstrapCode class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package bootstrap.gii
 */

Yii::import('gii.generators.crud.CrudCode');

class BootstrapCode extends CrudCode
{
    // 20150620/Samuel/暂时屏蔽该方法
    /*
    public function generateControlGroup($modelClass, $column)
    {
        if ($column->type === 'boolean') {
            return "BsHtml::activeCheckBoxControlGroup(\$model,'{$column->name}')";
        } else {
            if (stripos($column->dbType, 'text') !== false) {
                return "BsHtml::activeTextAreaControlGroup(\$model,'{$column->name}',array('rows'=>6))";
            } else {
                if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                    $inputField = 'activePasswordControlGroup';
                } else {
                    $inputField = 'activeTextFieldControlGroup';
                }

                if ($column->type !== 'string' || $column->size === null) {
                    return "BsHtml::{$inputField}(\$model,'{$column->name}')";
                } else {
                    if (($size = $maxLength = $column->size) > 60) {
                        $size = 60;
                    }
                    return "BsHtml::{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength))";
                }
            }
        }
    }
     * 
     */
    
   
    public function generateActiveControlGroup($modelClass, $column, $pattern=array())
    {   
        // 20150620/Samuel
        // 先检测$modelClass，其生成规则优先于列的数据类型规则
        $display = $pattern['display'];
        $data = $pattern['data'];
        if($display === 'pic'){
            return "\$form->uploadPicControlGroup(\$model,'{$column->name}')";
        }else if($display === 'pics'){
            return "\$form->uploadPicsControlGroup(\$model,'{$column->name}')";
        }else if($display === 'select'){
            return "\$form->dropDownListControlGroup(\$model,'{$column->name}', {$data})";
        }else if($display === 'text'){
            return "\$form->textAreaControlGroup(\$model,'{$column->name}',array('rows'=>6))";
        }else if($display === 'strings'){
            return "\$form->stringsControlGroup(\$model,'{$column->name}')";
        }else if($display === 'choice'){
            return "\$form->choiceControlGroup(\$model,'{$column->name}', {$data})";
        }
        
        if ($column->type === 'boolean') {
            return "\$form->checkBoxControlGroup(\$model,'{$column->name}')";
        }else if(preg_match('/(int)/i', $column->dbType)){
            return "\$form->numberFieldControlGroup(\$model,'{$column->name}')";
        }else if(preg_match('/(text)/i', $column->dbType)){
            return "\$form->textAreaControlGroup(\$model,'{$column->name}',array('rows'=>6))";
        }else if(preg_match('/^(date)$/i', $column->dbType)){
            return "\$form->dateControlGroup(\$model,'{$column->name}')";
        }else if(preg_match('/^(time)$/i', $column->dbType)){
            return "\$form->timeControlGroup(\$model,'{$column->name}')";
        }else if(preg_match('/^(datetime)$/i', $column->dbType)){
            return "\$form->datetimeControlGroup(\$model,'{$column->name}')";
        }else {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                $inputField = 'passwordFieldControlGroup';
            } else {
                $inputField = 'textFieldControlGroup';
            }

            if ($column->type !== 'string' || $column->size === null) {
                return "\$form->{$inputField}(\$model,'{$column->name}')";
            } else {
                return "\$form->{$inputField}(\$model,'{$column->name}',array('maxlength'=>$column->size))";
            }
        }
    }
}
