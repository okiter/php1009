<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/9
 * Time: 15:52
 */

namespace Admin\Controller;


use Think\Controller;

class GiiController extends Controller
{

    public function index(){
        if(IS_POST){
            header('Content-Type: text/html;charset=utf-8');
            //>>1.根据用户传递过来的表名，
            $table_name = I('post.table_name');
            //>>2.通过表名生成thinkphp中的规范的名称  goods==>Goods  goods_type==>GoodsType
            $name = parse_name($table_name,1);

            //>>3.通过表名得到表的注解
            $sql = "select  TABLE_COMMENT from information_schema.`TABLES`  where TABLE_SCHEMA = '".C('DB_NAME')."' and TABLE_NAME = '{$table_name}'";
            $model = M();
            $rows = $model->query($sql);
            $meta_title = $rows[0]['table_comment'];

            //>>4. 查询表中的字段信息. 为index.html,edit.html和模型提供数据
            $sql = "show full columns from ".$table_name;
            $fields = $model->query($sql);  //fields中包含了当前表字段的信息
            //对fields进一步处理, 从每列中的comment中提取列的名字,  表单类型, 以及可选值

            foreach($fields as $k=>&$field){
                 if($field['field']=='id'){
                    unset($fields[$k]);   //将id字段从fields中删除
                 }

                $comment = $field['comment'];
                 if(strpos($comment,'@')!==false){
                     $pattern  = '/(.*)@([a-z]*)\|?(.*)/';  //通过正则表达式将注解中的  名字,表单类型以及可选值提取出来
                     preg_match($pattern,$comment,$result);
                     $field['comment'] = $result[1];
                     $field['field_type'] = $result[2];
                     if(!empty($result[3])){
                         parse_str($result[3],$option_values);
                         $field['option_values'] = $option_values;
                     }
                 }
            }
            unset($field);
            //定义代码模板的目录
            defined('TPL_PATH') or define('TPL_PATH',ROOT_PATH.'Template/');

            //>>生成控 制器
            require TPL_PATH.'Controller.tpl';
            $controller_content = "<?php\r\n".ob_get_clean();

            $controller_path = APP_PATH.'Admin/Controller/'.$name.'Controller.class.php';
            file_put_contents($controller_path,$controller_content);


            //>>生成模型
            ob_start();//再次开启ob缓存
            require TPL_PATH.'Model.tpl';
            $model_content = "<?php\r\n".ob_get_clean();

            $model_path = APP_PATH.'Admin/Model/'.$name.'Model.class.php';
            file_put_contents($model_path,$model_content);

            //>>生成edit
            ob_start();//再次开启ob缓存
            require TPL_PATH.'edit.tpl';
            $edit_content = ob_get_clean();
            $edit_dir = APP_PATH.'Admin/View/'.$name;
            if(!is_dir($edit_dir)){ //如果存放视图文件是否存在,如果不存在就创建
                mkdir($edit_dir,0777,true);
            }
            $edit_path = $edit_dir.'/edit.html';
            file_put_contents($edit_path,$edit_content);

            //>>生成index
            ob_start();//再次开启ob缓存
            require TPL_PATH.'index.tpl';
            $index_content = ob_get_clean();
            $index_dir = APP_PATH.'Admin/View/'.$name;
            if(!is_dir($index_dir)){ //如果存放视图文件是否存在,如果不存在就创建
                mkdir($index_dir,0777,true);
            }
            $index_path = $index_dir.'/index.html';
            file_put_contents($index_path,$index_content);


            $this->success('生成成功!',U('index'));
        }else{
            $this->assign('meta_title','代码生成器');
            $this->display('index');
        }


    }

}