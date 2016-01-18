<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{

    public function index(){
        //>>1.查询出商品分类数据
        $goodsCategoryModel = D('GoodsCategory');
        $goodsCategorys = $goodsCategoryModel->getList();
        $this->assign('goodsCategorys',$goodsCategorys);






        $this->assign('meta_title','首页');
        $this->display('index');
    }

    public function lst(){
        $this->assign('meta_title','商品列表');

        //控制菜单是否显示
        $this->assign('is_hide',true);
        $this->display('lst');
    }

    public function goods(){
        $this->assign('meta_title','xxxx商品名称');
        //控制菜单是否显示
        $this->assign('is_hide',true);

        $this->display('goods');
    }

}