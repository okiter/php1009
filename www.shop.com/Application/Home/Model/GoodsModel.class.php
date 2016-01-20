<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/19
 * Time: 10:39
 */

namespace Home\Model;


use Think\Model;

class GoodsModel extends Model
{

    /**
     * 查询出符合商品状态的商品数据
     * @param $goods_status
     * @param int $num
     */
    public function getGoodsByGoodsStatus($goods_status,$num=5){
        $rows = $this->field('id,name,shop_price,logo')->where(array('status'=>1))->where("goods_status&{$goods_status}={$goods_status}")->limit($num)->select();
        return  $rows;
        //select id,name,goods_status from goods  where goods_status&4=4 and status =1
    }

    /**
     * 根据id得到商品信息
     * @param $id
     */
    public function getGoods($id){
         $this->alias('obj')->field('obj.*,b.name as brand_name,gi.intro as intro')->join('__BRAND__ as b on obj.brand_id = b.id');
         $this->join('__GOODS_INTRO__ as gi on obj.id = gi.goods_id');
         $row = $this->where(array('obj.id'=>$id))->find();
        return $row;
    }
}