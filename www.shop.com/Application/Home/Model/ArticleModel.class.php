<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/19
 * Time: 10:17
 */

namespace Home\Model;


use Think\Model;

class ArticleModel extends Model
{
    /**
     * 获取帮助文章
     */
    public function gethelpArticle(){
        $rows = S('HELP_ARTICLE');
        if(empty($rows)){
            $sql = "select  obj.id,obj.name,obj.article_category_id from article as obj  join article_category as ac on obj.article_category_id = ac.id and ac.is_help = 1";
            $rows = $this->query($sql);
            S('HELP_ARTICLE',$rows);
        }
        return $rows;
    }

}