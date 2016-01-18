<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/9
 * Time: 11:29
 */

namespace Admin\Controller;


use Think\Controller;

class BaseController extends Controller
{
    protected $model;

    //是否使用post中所有数据?
    protected $usePostParams  = false;

    public function _initialize()
    {
        $this->model = D(CONTROLLER_NAME);
    }

    public function index()
    {
        //>>2.使用模型查询数据库中状态>-1数据
        /**
         * $pageResult = >array(
         * 'rows'=>二维数组.   分页列表数据
         * 'pageHtml'=>分页工具条的html.
         * )
         */

        //得到查询的关键字
        $keyword = I('get.keyword', '');
        $wheres = array();  //专门用来存放查询的条件
        if (!empty($keyword)) {
            $wheres['name'] = array("like", "{$keyword}%");
        }

        //为wheres中准备查询条件
        $this->_setWheres($wheres);


        $pageResult = $this->model->getPageResult($wheres);
        //>>3.将数据分配到页面上
        $this->assign($pageResult);

        //>>将当前url地址保存到cookie中
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->assign('meta_title',$this->meta_title);
        //在index.html展示之前要执行的代码
        $this->_index_view_before();
        //>>4.选择视图页面
        $this->display('index');
    }

    /**
     * 主要是被覆盖, 为列表中指定查询条件
     * @param $wheres
     */
    protected function _setWheres(&$wheres){

    }


    //主要是被子类覆盖. 为index.html展示之前提供数据
    protected function _index_view_before(){

    }


    /**
     * 根据id将其状态修改为status的值
     * @param $id
     * @param $status  默认值为-1表示删除
    */
    public function changeStatus($id, $status = -1)
    {
        //>>1.创建模型对象
        //>>2.使用模型中的changeStatus修改数据的状态
        $result = $this->model->changeStatus($id, $status);
        if ($result !== false) {
            $this->success('操作成功!', cookie('__forward__'));
        } else {
            $this->error('操作失败!' . show_model_error($this->model));
        }
    }

    public function add()
    {
        if (IS_POST) {
            //>>1.创建模型对象
            //>>2.使用模型中的create方法进行收集数据并且验证
            if ($this->model->create() !== false) {
                //>>3.请求数据添加到数据库中
                if ($this->model->add($this->usePostParams?I('post.'):'') !== false) {
                    $this->success('添加成功!', cookie('__forward__'));
                    return;//防止下面的代码继续执行.
                }
            }
            $this->error('操作失败!' . show_model_error($this->model));
        } else {
            $this->assign('meta_title', '添加' . $this->meta_title);
            $this->_edit_view_before();
            $this->display('edit');
        }
    }

    /**
     * 该方法主要是被子类覆盖, 为编辑页面展示之前准备数据.
     */
    protected function _edit_view_before(){

    }


    /**
     * 根据id进行编辑
     * @param $id
     */
    public function edit($id)
    {
        if (IS_POST) {
            //>>1.使用模型中的create来接收请求参数
            if ($this->model->create() !== false) {
                //>>2.将请求参数修改到数据库中
                if ($this->model->save($this->usePostParams?I('post.'):'') !== false) {
                    $this->success('修改成功!', cookie('__forward__'));
                    return;
                }
            }
            $this->error('操作失败!' . show_model_error($this->model));
        } else {
            //>>1.使用模型查询出id对应的数据
            $row = $this->model->find($id);
            //>>2.将数据分配到页面上
            $this->assign($row);
            //>>3.显示edit页面回显数据
            $this->assign('meta_title', '编辑' . $this->meta_title);
            $this->_edit_view_before();
            $this->display('edit');
        }
    }


}