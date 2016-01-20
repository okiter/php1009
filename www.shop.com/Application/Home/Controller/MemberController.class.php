<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/19
 * Time: 15:46
 */

namespace Home\Controller;


use Org\Util\String;
use Think\Controller;

class MemberController extends Controller
{

    public  function reg(){
        if(IS_POST){

            //>>1.对短信验证码进行验证
            $tel_code = I('post.tel_code');
            $tel = I('post.tel');//电话号码
            $redis_code = S($tel);

            if($tel_code!=$redis_code){
                $this->error('短信验证码错误!');
            }
            //>>2.才收集请求中的参数, 将参数保存到数据库中.
            $memberModel = D('Member');
            if($memberModel->create()!==false){
                if($memberModel->add()!==false){
                    $this->success('注册成功!',U('login'));
                    return;
                }
            }
            $this->error(show_model_error($memberModel));
        }else{
            $this->display('reg');
        }
    }

    public  function login(){
        if(IS_POST){
            $memberModel = D('Member');
            if($memberModel->create()!==false){
                 $userinfo = $memberModel->login();
                 if(is_array($userinfo)){
                     //如果userinfo是一个数组表示用户信息
                        session('USERINFO',$userinfo);
                     $this->success('登陆成功!',U('Index/index'));
                 }else{
                     $this->error(show_model_error($memberModel));
                 }
            }
        }else{
            $this->display('login');
        }
    }


    public function logout(){
        session('USERINFO',null);
        $this->success('注销成功!',U('Index/index'));
    }


    public function check(){
        $param =  I('get.');
        $memberModel = D('Member');
        $result = $memberModel->check($param);
        //因为JavaScript需要的是一个json数据 必须将result传递给ajaxReturn作为json的内容
        $this->ajaxReturn($result);
    }


    /**
     * 获取短信的验证码
     * @param $tel
     */
    public function getSMSCode($tel){
        //随机码
       $str = String::randNumber(1000,9999);
        //>>1.将验证码保存到redis中.保存30秒
        S($tel,$str,300);
        //>>2.发送短信
       sendSMS('注册验证','{"code":"'.$str.'","product":"京西商城"}',$tel,'SMS_2245271');
    }


    /**
     * 对验证码进行验证
     * @param $tel_code
     * @param $tel
     */
    public function checkTel($tel_code,$tel){
        //>>1.取出redis中的验证码
            $redis_code = S($tel);  //根据电话号码取出redis中的验证码
        //>>2. 再对验证码进行验证
            $reuslt = $tel_code==$redis_code;

            $this->ajaxReturn($reuslt);  //因为remote验证规则需要的json的boolean类型的数据
    }

    public function fire($id,$key){
        $memberModel = D('Member');
        $result = $memberModel->fire($id,$key);
        if($result===false){
            $this->error('激活失败!');
        }else{
            $this->success('激活成功!',U('login'));
        }
    }




}