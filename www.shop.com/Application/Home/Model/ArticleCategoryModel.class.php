<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/19
 * Time: 9:46
 */

namespace Home\Model;


use Think\Model;

class ArticleCategoryModel extends Model
{
    /**
     * 获取帮助类的分类数据
     */
    public function getHelpArticleCategory(){
        $rows  = S('HELP_ARTICLE_CATEGORY');
        if(empty($rows)){
            $rows = $this->field('id,name')->where(array('is_help'=>1,'status'=>1))->select();
            S('HELP_ARTICLE_CATEGORY',$rows);
        }
        return $rows;
    }
}