<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/20
 * Time: 10:34
 */

namespace Home\Model;


use Think\Model;

class MemberModel extends Model
{
    protected $_auto = array(
        array('salt','\Org\Util\String::randString','','function'),
        array('regtime',NOW_TIME),
        array('reg_ip','get_client_ip_bigint','','callback'),
    );


    /**
     * 覆盖add方法
     */
    public function add(){
        $email = $this->data['email'];

        $this->data['password']  = md5(md5($this->data['password']).$this->data['salt']);
        $id = parent::add();

        $key = md5($email);
        $mail_content = "<a href='http://www.shop.com/index.php/Member/fire/id/{$id}/key/$key' target='_blank'>点击我激活账号</a>";
        //准备发送一封激活邮件
        $result = sendMail($email,'京西商城的激活邮件',$mail_content);
        if($result===false){
            $this->error = '发送邮件失败!';
            return false;
        }

        return $id;
    }


    public function get_client_ip_bigint(){
        $ip = get_client_ip();
        return ip2long($ip);
    }

    /**
     * 检查参数上面的值是否在数据库中存在
     * @param string $param
     * @return bool
     */
    public function check($param){
        //得到键
        $keys = array_keys($param);
        $key = $keys[0];
        //查询数据库中是否存在该值
        $count = $this->where(array('status'=>1,$key=>$param[$key]))->count();
        return $count == 0;
    }


    public function fire($id,$key){
        $email = $this->getFieldById($id,'email');
        if(md5($email)==$key){
            return $this->where(array('id'=>$id))->setField('status',1);
        }else{
            $this->error = '激活失败!';
            return false;
        }
    }


    public function login(){
            $username = $this->data['username'];
            $password = $this->data['password'];
          //>>1.判断用户名是否存在
            $row = $this->field('id,username,password,salt,status')->where(array('status'=>array('gt',-1)))->getByUsername($username);
            if(empty($row)){
                $this->error = '该用户不存在!';
                return false;
            }

            if($row['status']==='0'){
                $this->error = '该用户未激活或者被锁定';
                return false;
            }
          //>>2.判断密码是否和数据库中的密码一致
            if($row['password']==md5(md5($password).$row['salt'])){
                   //密码对比上之后才登陆成功

                   //将登陆成功后的IP和时间更新到数据库表中
                   parent::save(array('last_login_time'=>NOW_TIME,'last_login_ip'=>ip2long(get_client_ip()),'id'=>$row['id']));

                return $row;
            }else{
                $this->error = '密码不正确!';
                return false;
            }

    }
}