<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{

    /**
     * 该方法被构造函数调用...  下面其他的所有方法执行之前都会调用该方法.
     */
    public function _initialize(){
        //>>1.查询出商品分类数据
        $goodsCategoryModel = D('GoodsCategory');
        $goodsCategorys = $goodsCategoryModel->getList();
        $this->assign('goodsCategorys',$goodsCategorys);

        //>>2.查询出帮助类的文章分类数据
        $articleCategoryModel = D('ArticleCategory');
        $helpArticleCategorys = $articleCategoryModel->getHelpArticleCategory();
        $this->assign('helpArticleCategorys',$helpArticleCategorys);

        //>>3. 从文章表中查询出属于帮助类的文章
        $articleModel = D('Article');
        $helpArticles = $articleModel->gethelpArticle();
        $this->assign('helpArticles',$helpArticles);
    }




    public function index(){
        //准备不同状态的商品数据
        $goodsModel = D('Goods');
        //查询出 推荐商品
        $is_1s = $goodsModel->getGoodsByGoodsStatus(1);  //推荐商品
        $is_2s = $goodsModel->getGoodsByGoodsStatus(2);
        $is_4s = $goodsModel->getGoodsByGoodsStatus(4);
        $is_8s = $goodsModel->getGoodsByGoodsStatus(8);
        $is_16s = $goodsModel->getGoodsByGoodsStatus(16);
        $this->assign(array(
            'is_1s'=>$is_1s,
            'is_2s'=>$is_2s,
            'is_4s'=>$is_4s,
            'is_8s'=>$is_8s,
            'is_16s'=>$is_16s,
        ));




        $this->assign('meta_title','首页');
        $this->display('index');
    }

    public function lst(){
        $this->assign('meta_title','商品列表');

        //控制菜单是否显示
        $this->assign('is_hide',true);
        $this->display('lst');
    }


    /**
     * 根据商品id查询出当前商品的所有数据
     * @param $id
     */
    public function goods($id){
        //>>1.查询出商品id对应到当前商品内容
        $goodsModel = D('Goods');
        $goods = $goodsModel->getGoods($id);
        $this->assign($goods);

        //>>2.查询出当前商品分类对应的祖父分类
        $goodsCategoryModel = D('GoodsCategory');
        $parents = $goodsCategoryModel->getParents($goods['goods_category_id']);
        $this->assign('parents',$parents);

        //>>3.查询出当前商品对应的图片数据
        $goodsPhotoModel = D('GoodsPhoto');
        $paths = $goodsPhotoModel->getPathByGoodsId($id);
        array_unshift($paths,$goods['logo']);
        $this->assign('paths',$paths);

        //>>4.记录用户最近浏览的商品, 浏览器的商品id放在浏览器的cookie中
            //>>4.1先从cookie中得到浏览记录,先创建一个数组存放历史记录
            $historys = cookie('historys');
            if(empty($historys)){
                $historys = array();
            }else{
                $historys = unserialize($historys);
            }

            //>>4.2 检查历史记录中是否有重复的商品, 如果有需要将其删除
             foreach($historys as $k=>$history){
                  if($history['id']==$id){
                        unset($historys[$k]);
                        break;
                  }
             }



             //将之前的历史记录分配到页面上显示
             $this->assign('historys',$historys);


            //>>4.3. 再将当前商品信息放到历史记录中
              $history = array('id'=>$id,'name'=>$goods['name'],'logo'=>$goods['logo']);
              array_unshift($historys,$history);
            //>>4.4. 需要重新将history放到cookie中
             cookie('historys',serialize($historys));  //不能够放二维数组, 需要将其序列化

        $this->assign('meta_title',$goods['name']);
        //控制菜单是否显示
        $this->assign('is_hide',true);

        $this->display('goods');
    }

}