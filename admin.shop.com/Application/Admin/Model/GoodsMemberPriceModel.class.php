<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/16
 * Time: 10:20
 */

namespace Admin\Model;


use Think\Model;

class GoodsMemberPriceModel extends Model
{

    /**
     * 根据商品id查找到对应的会员价格
     * @param $goods_id
     * @return array
     * array(
            会员级别ID=>价格,
            会员级别ID=>价格,
            会员级别ID=>价格,
            );

     */
    public function getMemberPrice($goods_id){
        $rows = $this->field('member_level_id,price')->where(array('goods_id'=>$goods_id))->select();
        //>>1.取出rows中的member_level_id
        $member_level_ids = array_column($rows,'member_level_id');
        //>>2.取出rows中的price
        $prices = array_column($rows,'price');

        //>>3.将member_level_ids作为键,  prices作为值
        $row = array_combine($member_level_ids,$prices);
        return $row;
    }

}