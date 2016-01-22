<?php
namespace Admin\Model;


use Think\Model;
use Think\Page;

class GoodsModel extends BaseModel
{
    // 每个表单都有自己的验证规则
    protected $_validate_1 = array(
        array('name','require','商品名称不能够为空'),
        array('short_name','require','简称不能够为空'),
        array('sn','require','货号不能够为空'),
        array('goods_category_id','require','商品分类不能够为空'),
        array('brand_id','require','商品品牌不能够为空'),
        array('supplier_id','require','供货商不能够为空'),
        array('shop_price','require','本店价格不能够为空'),
        array('market_price','require','市场价格不能够为空'),
        array('stock','require','库存不能够为空'),
        array('goods_status','require','商品状态不能够为空'),
        array('status','require','是否显示不能够为空')
    );


    protected function _handleRows(&$rows){
        //对列表中的数据进一步处理
        foreach($rows as &$row){
            $row['is_best'] = ($row['goods_status'] & 1);
            $row['is_new'] =  (($row['goods_status'] & 2) >>1);
            $row['is_hot'] =  (($row['goods_status'] & 4) >>2);
        }
        unset($row);  //避免引用传值发生错误
    }



    /**
     * 该方法主要是被子类覆盖..
     */
    protected function _setModel(){
        //连接其他表
        $this->join('__GOODS_CATEGORY__ as gc on obj.goods_category_id = gc.id ');
        $this->join('__BRAND__ as b on obj.brand_id = b.id ');
        $this->join('__SUPPLIER__ as s on obj.supplier_id = s.id ');
        //取出其他表中的name
        $this->field('obj.*,gc.name as goods_category_name,b.name as brand_name,s.name as supplier_name');
    }



    /**
     * 不仅仅是将请求中的数据保存到goods表中,也需要将请求中的数据保存到其他表中. 例如: goods_intro
     */
    public  function add($requestData){
        //requestData包含: 请求中的所有数据
        //$this->data包含: 包含goods表中需要的数据

             $this->startTrans();//开启事务

            //将请求中的商品状态
            $this->handleGoodsStatus();
        //>>1.将this->data中数据保存到goods表中
            $id = parent::add();
            if($id===false){
                $this->rollback();
                return false;
            }

            //计算出当前商品的货号,  将货号更新到当前商品中
            $sn = date('Ymd').str_pad($id, 9, "0", STR_PAD_LEFT);
            $result = parent::save(array('id'=>$id,'sn'=>$sn));
            if($result===false){
                $this->error = '保存货号时失败!';
                $this->rollback();
                return false;
            }
        //>>2.将商品描述保存到goods_intro表中
            $goodsIntroModel = M('GoodsIntro');
            $result = $goodsIntroModel->add(array('goods_id'=>$id,'intro'=>$requestData['intro']));
            if($result===false){
                $this->error = '保存商品描述失败!';
                $this->rollback();
                return false;
            }

        //>>3.保存商品的会员价格
         $result = $this->handleMemberPrice($id,$requestData['memberPrices']);
         if($result===false){
             return false;
         }


        //>>4.处理商品照片
        $result = $this->handleGoodsPhoto($id,$requestData['goods_photo_paths']);
        if($result===false){
            return false;
        }

        //>>5.处理相关文章
        $result = $this->handleGoodsArticle($id,$requestData['article_ids']);
        if($result===false){
            return false;
        }



        $this->commit();//提交事务

        return $id;
    }

    /**
     * 将相关文章保存到 goods_article表中
     * @param $goods_id
     * @param $article_ids
     */
    private function handleGoodsArticle($goods_id,$article_ids){

        $goodsArticleModel = M('GoodsArticle');
        $result = $goodsArticleModel->where(array('goods_id'=>$goods_id))->delete();
        if($result===false){
            $this->error = '删除相关文章失败!';
            $this->rollback();
            return false;
        }

        $rows = array();
        foreach($article_ids as $article_id){
             $rows[] = array('goods_id'=>$goods_id,'article_id'=>$article_id);
        }
        if(!empty($rows)){
            $result = $goodsArticleModel->addAll($rows);
            if($result===false){
                $this->error = '保存相关文章失败!';
                $this->rollback();
                return false;
            }
        }

    }

    /**
     * 处理照片数据
     * @param $goods_id
     * @param $goods_photo_paths
     * @return bool
     */
    private function handleGoodsPhoto($goods_id,$goods_photo_paths){

        $goodsPhotoModel = M('GoodsPhoto');

        $rows = array();
        foreach($goods_photo_paths as $path){
            $rows[] = array('goods_id'=>$goods_id,'path'=>$path);
        }

        if(!empty($rows)){
            $result = $goodsPhotoModel->addAll($rows);
            if($result===false){
                $this->error = '保存照片时失败!';
                $this->rollback();
                return false;
            }
        }
    }


    /**
     * 保存当前商品的会员价格
     * @param $goods_id
     * @param $memberPrices
     */
    private function handleMemberPrice($goods_id, $memberPrices)
    {
        $goodsMemberPriceModel = M('GoodsMemberPrice');

        /**
         * 先删除,  如果添加方法使用到该函数, 不会删除任何数据
         */
        $result = $goodsMemberPriceModel->where(array('goods_id'=>$goods_id))->delete(); //delete from goods_member_price where goods_id = 7
        if($result===false){
            $this->rollback();
            $this->error = '删除会员价格失败!';
            return false;
        }


        /**
         * 再添加
         */
        //要保存当前商品的多个会员价格
        $rows = array();
        foreach ($memberPrices as $member_level_id => $price) {
            $rows[] = array('goods_id' => $goods_id, 'member_level_id' => $member_level_id, 'price' => $price);
        }
        if (!empty($rows)) {
            $result = $goodsMemberPriceModel->addAll($rows);
            if ($result === false) {
                $this->error = '添加会员价格失败!';
                $this->rollback();
                return false;
            }
        }

    }

    /**
     * 计算请求中的商品状态
     */
    private function handleGoodsStatus(){
        $goods_status = 0; //初始状态为 0
        foreach($this->data['goods_status'] as $gs){
            $goods_status = $goods_status | $gs;
        }
        //计算完之后,重新放将goods_status到this->data中.
        $this->data['goods_status'] = $goods_status;
    }


    /**
     * 不仅仅包this->data中的数据更新到数据表goods中, 还需要将其他的数据更新到其他的表中  例如:goods_intro
     * @param mixed|string $requestData
     */
    public function save($requestData){
        $goods_id = $this->data['id'];

        $this->startTrans();//开启事务

        //>>1.将this->data中的数据更新到goods表中
        $this->handleGoodsStatus();//计算goods_status的值
        $result = parent::save();
        if($result===false){
            $this->rollback();
            return false;
        }
        //>>2.将intro数据更新到goods_intro表中
        $goodsIntroModel = M('GoodsIntro');
        $result1 = $goodsIntroModel->save(array('goods_id'=>$goods_id,'intro'=>$requestData['intro']));
        if($result1===false){
            $this->error = '更新商品描述失败!';
            $this->rollback();
            return false;
        }

        //>>3.处理会员价格(新将原来的数据删除, 再添加新的请求数据)
        $result = $this->handleMemberPrice($goods_id,$requestData['memberPrices']);
        if($result===false){
            return false;
        }

        //>>4.处理商品相册
        $result = $this->handleGoodsPhoto($goods_id,$requestData['goods_photo_paths']);
        if($result===false){
            return false;
        }

        //>>5.处理相关文章
        $result = $this->handleGoodsArticle($goods_id,$requestData['article_ids']);
        if($result===false){
            return false;
        }



        $this->commit();
        return $result;
    }


}