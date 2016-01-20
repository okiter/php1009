<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/18
 * Time: 16:24
 */

namespace Home\Model;


use Think\Model;

class GoodsCategoryModel extends Model
{

    /**
     * 查询出所有的数据
     * @return mixed
     */
    public function getList(){
        //>>1.从缓存中获取
        $rows  = S('GOODS_CATEGORY');
        if(empty($rows)){
            //>>2.如果缓存中没有,就从数据库中查询
            $rows = $this->field('id,name,parent_id,level')->where(array('status'=>1))->order('lft')->select();
            //>>3. 放到redis缓存中.. 为了下一次从redis中获取
            S('GOODS_CATEGORY',$rows);
        }

        return $rows;
    }

    /**
     * 根据一个分类的id得到祖父分类(包含自己)
     * @param $goods_category_id
     */
    public function getParents($goods_category_id){
        $sql = "select  parent.name,parent.id from goods_category as parent,goods_category as child where parent.lft<=child.lft and parent.rgt>=child.rgt  and child.id = {$goods_category_id}  order by parent.lft";
        return  $this->query($sql);
    }
}