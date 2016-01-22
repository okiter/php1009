<?php
namespace Admin\Model;


use Think\Model;
use Think\Page;

class ArticleModel extends BaseModel
{
    protected $_auto = array(
        array('inputtime',NOW_TIME)
    );
    // 每个表单都有自己的验证规则
    protected $_validate = array(
        array('name','require','文章名称不能够为空'),
        array('article_category_id','require','文章分类ID不能够为空'),
        array('inputtime','require','录入时间不能够为空'),
        array('status','require','是否显示不能够为空'),
    );

    protected function _setModel(){
        //连接另外一个表
        $this->join('__ARTICLE_CATEGORY__ as ac on obj.article_category_id = ac.id');
        //指定查询出表中的字段
        $this->field('obj.*,ac.name as article_category_name');
    }


    /**
     * 接收请求中的所有数据
     * @param mixed|string $requestData
     */
    public function add($requestData){
        //>>1.需要将create收集到的数据保存到article表中
            $id = parent::add();
            if($id===false){
                return false;
            }
        //>>2.需要将请求中的content数据保存到article_content表中
            $articleContentModel = M('ArticleContent');
            $result = $articleContentModel->add(array('article_id'=>$id,'content'=>$requestData['content']));
            if($result===false){
                return false;
            }
        return $id;
    }

    public function save($requestData){
        //>>1.需要将create收集到的数据更新到article表中
            $result  = parent::save();
            if($result===false){
                return false;
            }
            //>>2.需要将请求中的content数据更新到article_content表中
            $articleContentModel = M('ArticleContent');
            $result = $articleContentModel->where(array('article_id'=>$requestData['id']))->setField('content',$requestData['content']);
            if($result===false){
                $this->error = '更新文章内容失败!';
                return false;
            }
    }
}