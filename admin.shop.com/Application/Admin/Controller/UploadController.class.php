<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/12
 * Time: 15:15
 */

namespace Admin\Controller;


use Think\Controller;
use Think\Upload;

class UploadController extends Controller
{
    public function index(){
        $dir = I('post.dir');  //获取浏览器指定的服务(空间)
        $config = C('UPLOAD_CONFIG');

        //在配置中添加 空间
        $config['driverConfig']['bucket'] = $dir;

        $uploader = new Upload($config);
        $result = $uploader->uploadOne($_FILES['Filedata']);
        if($result!==false){
            //将上传后的路径发送给浏览器
           exit($result['savepath'].$result['savename']); //保存到upyun上的地址
        }else{
            echo $uploader->getError();
        }
    }

}