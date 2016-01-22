<?php
namespace Admin\Controller;


use Think\Controller;

class GoodsController extends BaseController
{
    protected $meta_title = '商品';

    //告诉父类中的add和edit方法通过I('post.')接收到请求中的所有数据.并且传递给模型类
    protected $usePostParams  = true;

    protected function _setWheres(&$wheres){
        //如果供货商不为空
        $supplier_id = I('get.supplier_id');
        if(!empty($supplier_id)){
            $wheres['obj.supplier_id'] = $supplier_id;
        }

        //如果品牌部不为空, 向wheres中放查询条件
        $brand_id = I('get.brand_id');
        if(!empty($brand_id)){
            $wheres['obj.brand_id'] = $brand_id;
        }

        //如果商品分类的id部不为空, 向wheres中放查询条件
        $goods_category_id = I('get.goods_category_id');
        if(!empty($goods_category_id)){
            $goodsCategroyModel = D('GoodsCategory');
            $leafIds = $goodsCategroyModel->getLeaf($goods_category_id);
            $wheres['obj.goods_category_id'] = array('in',$leafIds);
        }


    }


    //index展示志为页面提供供应商和品牌的数据
    protected function _index_view_before(){
        //查询供货商和品牌的数据供index页面使用
        $supplierModel  =  D('Supplier');
        $suppliers = $supplierModel->getList('id,name');
        $this->assign('suppliers',$suppliers);

        $brandModel  =  D('Brand');
        $brands = $brandModel->getList('id,name');
        $this->assign('brands',$brands);


        //查询出商品分类数据 提供也index页面
        $goodsCategoryModel = D('GoodsCategory');
        $zNodes = $goodsCategoryModel->getTreeList(true,'id,name,parent_id');
        $this->assign('zNodes',$zNodes);
    }



    /**
     * 该方法主要是被子类覆盖, 为编辑页面展示之前准备数据.
     */
    protected function _edit_view_before(){
        //>>1.查询出商品分类的数据
        $goodsCategoryModel = D('GoodsCategory');
        $rows = $goodsCategoryModel->getTreeList(true,'id,name,parent_id');
        //>>2.分配到页面上
        $this->assign('zNodes',$rows);


        //>>3. 准备商品品牌数据
        $brandModel = D('Brand');
        $brands = $brandModel->getList('id,name');
        $this->assign('brands',$brands);

        //>>4.准备供应商的数据
        $supplierModel = D('Supplier');
        $suppliers = $supplierModel->getList('id,name');
        $this->assign('suppliers',$suppliers);

        //>>5.准备会员级别数据
        $memberLevelModel = D('MemberLevel');
        $memberLevels = $memberLevelModel->getList('id,name');
        $this->assign('memberLevels',$memberLevels);


        //>>5.判断是否为编辑时
        $id = I('get.id');
        if(!empty($id)){
            //>>5.1 编辑时从goods_intro中查询出当前商品对应的商品简介
            $goodsIntroModel = M('GoodsIntro');
            $intro = $goodsIntroModel->getFieldByGoods_id($id,'intro');
            $this->assign('intro',$intro);

            //>>5.2.编辑时获取当前商品对应的会员价格
            $goodsMemberPriceModel = D('GoodsMemberPrice');
            $memberPrices = $goodsMemberPriceModel->getMemberPrice($id);
            $this->assign('memberPrices',$memberPrices);

            //>>5.3 编辑时要查询出当前商品对应的照片数据
            $goodsPhotoModel = M('GoodsPhoto');
            $goodsPhotos = $goodsPhotoModel->field('id,path')->where(array('goods_id'=>$id))->select();
            $this->assign('goodsPhotos',$goodsPhotos);

            //>>5.4 编辑时查询出当前商品的相关文章
            $goodsArticleModel = D('GoodsArticle');
            $articles = $goodsArticleModel->getAricle($id);
            $this->assign('articles',$articles);


        }


    }

}