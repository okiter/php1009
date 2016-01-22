<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/20
 * Time: 16:55
 */

namespace Home\Controller;


use Think\Controller;

class ShoppingCarController extends Controller
{
    public function index(){
        $shoppingCarModel = D('ShoppingCar');
        $rows = $shoppingCarModel->getList();  //得到购物车的列表数据
        dump($rows);
    }




    /**
     * 将请求中的数据添加到购物车中
     */
    public function add(){
        $shoppingCarModel = D('ShoppingCar');
        $result = $shoppingCarModel->add(I('post.'));
        if($result!==false){
            $this->success('添加成功!',U('index'));
        }else{
            $this->error('添加失败!');
        }
    }

}