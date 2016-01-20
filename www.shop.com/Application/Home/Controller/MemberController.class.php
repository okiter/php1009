<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/19
 * Time: 15:46
 */

namespace Home\Controller;


use Think\Controller;

class MemberController extends Controller
{

    public  function reg(){
        if(IS_POST){

        }else{
            $this->display('reg');
        }
    }

    public  function login(){
        if(IS_POST){

        }else{
            $this->display('login');
        }
    }


}