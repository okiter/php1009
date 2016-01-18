<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/18
 * Time: 9:40
 */

namespace Admin\Model;


use Think\Model;

class GoodsArticleModel extends Model
{

    /**
     * 根据当前商品的id查询出相关文章
     * @param $goods_id
     */
    public function getAricle($goods_id){
//        SELECT obj.article_id,a.name FROM `goods_article` as obj join article as a on obj.article_id = a.id where  obj.goods_id = 18;
        $this->field('obj.article_id,a.name');
        $this->alias('obj')->join('__ARTICLE__ as a on obj.article_id = a.id');
        $this->where(array('obj.goods_id'=>$goods_id));
        return $this->select();
    }

}