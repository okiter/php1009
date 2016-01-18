<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/16
 * Time: 14:13
 */

namespace Admin\Controller;


use Think\Controller;

class GoodsPhotoController extends Controller
{

    /**
     * 根据id直接从数据库中删除
     * @param $id
     */
    public function remove($id){
        $goodsPhotoModel = M('GoodsPhoto');
        if($goodsPhotoModel->delete($id)!==false){
            $this->success('删除成功!');
        }else{
            $this->error('删除失败!');
        }
    }
}