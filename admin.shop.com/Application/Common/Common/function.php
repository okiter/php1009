<?php

/**
 * 获取model中的错误信息
 * @param $model
 * @return string  错误信息
 */
function show_model_error($model)
{
    //得到model中的错误信息
    $errors = $model->getError();
    $errorMsg = '<ul>';
    if (is_array($errors)) {
        //如果是数组将错误信息拼成一个ul
        foreach ($errors as $error) {
            $errorMsg .= "<li>{$error}</li>";
        }
    } else {
        $errorMsg .= "<li>{$errors}</li>";
    }
    $errorMsg .= '</ul>';
    return $errorMsg;
}

/**
 * 返回数组中指定的一列
 * @param $rows     二维数组
 * @param $field    字段
 * @return array   一维数组
 */
if(!function_exists('array_column')){   //做系统兼容性出来.
    function array_column($rows,$field){
        $value =array();
        foreach($rows as $row){ //循环出每个小数组,并且出去field字段对应的值.
            $value[] = $row[$field];
        }
        return $value;
    }
}


/**
 * 根据传入进来的参数生成下拉框的html
 * @param $name    下拉框的name的值
 * @param $rows    下拉框中的数据
 * @param string $defaultValue   默认值, 根据默认值可以选中其中的一个选项
 * @param string $valueField        使用该数据中该字段作为value的值
 * @param string $textField        使用该数据中该字段作为text的值
 */
function arr2select($name,$rows,$defaultValue='',$valueField='id',$textField='name'){
    $select_html = "<select class='{$name}' name='{$name}'>";
    $select_html .= "<option value=''>--请选择--</option>";
    foreach($rows as $row){

        //根据默认值选中一个选项
        $selected = '';
        if($row[$valueField]==$defaultValue){
            $selected = 'selected';
        }

        $select_html .= "<option value='{$row[$valueField]}' {$selected}>{$row[$textField]}</option>";
    }
    $select_html .= '</select>';
    echo $select_html;
}