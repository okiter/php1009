<?php
namespace Admin\Controller;


use Think\Controller;

class ArticleController extends BaseController
{
    protected $meta_title = '文章';

    //是否使用post中所有数据?
    protected $usePostParams  = true;

    public function _edit_view_before(){
        //>>1.准备文章分类数据
        $articleCategoryModel = D('ArticleCategory');
        $articleCategorys = $articleCategoryModel->getList('id,name');
        $this->assign('articleCategorys',$articleCategorys);

        $id = I('get.id');
        if(!empty($id)){
            //再编辑时 从article_content表中取出content的值
            $articleContentModel = M('ArticleContent');
            $content = $articleContentModel->getFieldByArticle_id($id,'content');
            $this->assign('content',$content);
        }
    }


    public function search($keyword){
        $wheres = array();
        if(!empty($keyword)){
            $wheres['name'] = array('like',"%{$keyword}%");
        }

        $rows = $this->model->getList('id,name',$wheres);
        //该方法会将传递进去的数据变成json发送给浏览器
        $this->ajaxReturn($rows);
    }


}