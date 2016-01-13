<?php
namespace Admin\Controller;


use Think\Controller;

class GoodsCategoryController extends BaseController
{
    protected $meta_title = '商品分类';

    public function index()
    {
        $rows = $this->model->getTreeList();
        $this->assign('rows',$rows);

        $this->assign('meta_title',$this->meta_title);

        //>>将当前url地址保存到cookie中
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        //>>4.选择视图页面
        $this->display('index');
    }


    public function add()
    {
        if (IS_POST) {
            //>>1.创建模型对象
            //>>2.使用模型中的create方法进行收集数据并且验证
            if ($this->model->create() !== false) {
                //>>3.请求数据添加到数据库中
                if ($this->model->add() !== false) {
                    $this->success('添加成功!', cookie('__forward__'));
                    return;//防止下面的代码继续执行.
                }
            }
            $this->error('操作失败!' . show_model_error($this->model));
        } else {
            $this->assign('meta_title', '添加' . $this->meta_title);

            //准备页面中ztree需要的数据
            $rows = $this->model->getTreeList(true,'id,name,parent_id');
            $this->assign('zNodes',$rows);

            $this->display('edit');
        }
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
                if ($this->model->save() !== false) {
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

            //>>4准备页面中ztree需要的数据
            $rows = $this->model->getTreeList(true,'id,name,parent_id');
            $this->assign('zNodes',$rows);

            $this->display('edit');
        }
    }

}