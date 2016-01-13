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


    protected function _edit_view_before(){
        //准备页面中ztree需要的数据
        $rows = $this->model->getTreeList(true,'id,name,parent_id');
        $this->assign('zNodes',$rows);
    }


}