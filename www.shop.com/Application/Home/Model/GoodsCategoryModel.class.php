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
        $rows = $this->field('id,name,parent_id,level')->where(array('status'=>1))->order('lft')->select();
        return $rows;
    }
}