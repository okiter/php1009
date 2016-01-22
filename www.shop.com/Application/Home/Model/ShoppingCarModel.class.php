<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/20
 * Time: 16:57
 */

namespace Home\Model;


class ShoppingCarModel
{

   public function add($requestData){
       $userinfo  = session('USERINFO');
       if($userinfo){
            $this->addDB($requestData);
       }else{
           $this->addCookie($requestData);
       }
   }


    /**
     * 将请求中的购物数据添加的cookie中
     * @param $requestData
     */
    public function addCookie($requestData){
            //>>1. 从cookie中得到购物车的数据
            $shoppingCar = cookie('shopping_car');
            if(empty($shoppingCar)){
                $shoppingCar = array();
            }else{
                $shoppingCar = unserialize($shoppingCar);
            }

            $tag = false; //当前商品没有在购物车中存在
           //>>2. 将请求中的购物数据放到数组中
            foreach($shoppingCar as &$item){
                //if成立,说明购物车中有商品
                if($item['goods_id']==$requestData['goods_id']){
                        $item['num'] =  $item['num'] + $requestData['num'];
                        $tag = true; //表示当前商品在购物车中存在
                    break;
                }
            }
            if(!$tag){
                $shoppingCar[] = $requestData;
            }



           //>>3.将购物数据序列化后放到cookie中
            cookie('shopping_car',serialize($shoppingCar));
    }

    /**
     * 将请求中的数据添加到数据库中
     * @param $requestData
     */
    public function addDB($requestData){

    }



    public function getList(){
        $userinfo  = session('USERINFO');
        if(empty($userinfo)){
            //从cookie中得到购物车中的数据
            $shoppingCar = cookie('shopping_car');
            if(empty($shoppingCar)){
                return false;
            }
            return unserialize($shoppingCar);
        }else{
            //从数据库中获取到购物数据

        }
    }
}