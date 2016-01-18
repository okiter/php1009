<?php
namespace Admin\Model;


use Admin\Service\NestedSetsService;
use Think\Model;

class GoodsCategoryModel extends BaseModel
{
    // 每个表单都有自己的验证规则
    protected $_validate = array(
        array('name','require','分类名称不能够为空'),
        array('parent_id','require','父分类不能够为空'),
        array('status','require','是否显示不能够为空'),
    );


    public function getTreeList($isJSON=false,$field='*'){
        $row = $this->field($field)->where(array('status'=>array('egt',0)))->order('lft')->select();
        if($isJSON){
            //将其变为json
            return json_encode($row);
        }
        return $row;
    }


    public function add(){
        //>>1.创建能够执行sql的对象
        $dbMysql = new DbMysqlInterfaceImplModel();

        //>>2.计算边界
        $nestedSetsService = new NestedSetsService($dbMysql,'goods_category','lft','rgt','parent_id','id','level');

        //添加的节点信息放到哪个父节点下. 并且返回该节点对应的id
        return $nestedSetsService->insert($this->data['parent_id'],$this->data,'bottom');
    }


    public function save(){
        //>>1.创建能够执行sql的对象
        $dbMysql = new DbMysqlInterfaceImplModel();

        //>>2.计算边界
        $nestedSetsService = new NestedSetsService($dbMysql,'goods_category','lft','rgt','parent_id','id','level');

        //>>3.将指定的节点移动一个父分类下面
        $nestedSetsService->moveUnder($this->data['id'],$this->data['parent_id']);

        //>>4.需要将请求中的其他数据修改到数据库中
        return parent::save();
    }


    /**
     * 不仅把自己的状态修改了,还需要修改子孙节点
     * @param $id
     * @param int $status
     * @return bool
     */
    public function changeStatus($id, $status = -1)
    {

        //>>1.根据自己的id找到自己以及子孙节点的id
        $sql = "select child.id from  goods_category as child,goods_category as parent where  parent.id = {$id}  and child.lft>=parent.lft  and child.rgt<=parent.rgt";
        $rows = $this->query($sql);
        $id  = array_column($rows,'id');
        $data = array('id' => array('in', $id), 'status' => $status);
        if ($status == -1) {
            $data['name'] = array('exp', "concat(name,'_del')");  //update supplier set name = concat(name,'_del'),status = -1    where id in (1,2,3);
        }
        return parent::save($data);
    }

    /**
     * 根据一个商品分类得到叶子节点的id
     * @param $goods_category_id
     */
    public function getLeaf($goods_category_id){
        $sql = "select child.id from goods_category as  parent,goods_category as child where  parent.id = {$goods_category_id} and child.lft>=parent.lft and child.rgt<=parent.rgt and child.lft+1 =child.rgt";
        $rows = $this->query($sql);
        //从二维数组中得到id的值
        $ids = array_column($rows,'id');
        return $ids;
    }

}