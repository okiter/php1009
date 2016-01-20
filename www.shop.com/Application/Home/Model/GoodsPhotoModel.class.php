<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/19
 * Time: 14:31
 */

namespace Home\Model;


use Think\Model;

class GoodsPhotoModel extends Model
{

    /**
     * 根据商品id查询出商品照片表中的照片路径
     * @param $goods_id
     */
    public function getPathByGoodsId($goods_id){
        $rows = $this->field('path')->where(array('goods_id'=>$goods_id))->select();
        return array_column($rows,'path');
    }

}