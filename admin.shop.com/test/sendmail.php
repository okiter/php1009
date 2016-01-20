<?php



//>>1. 通过126的账号登陆到126的邮箱服务器上
    /**
     * 1.1 需要126的账号和密码   itsource520   qqitsource520
     * 1.2 需要126服务器的IP地址(域名)   smtp.126.com
     */

require './PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output


//>>1.登陆邮件服务器
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.126.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'itsource520@126.com';                 // SMTP username
$mail->Password = 'qqitsource520';                           // SMTP password
//$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
//$mail->Port = 587;                                    // TCP port to connect to


$mail->CharSet = 'utf-8';//设置编码

//>>2.写邮件内容
$mail->setFrom('itsource520@126.com', '发件人的名字'); //指定发件人

$mail->addAddress('itsource520@126.com', 'Joe User');     // 收件人的名字

//$mail->addReplyTo('info@example.com', 'Information'); // 收件人
$mail->addCC('ggz@itsource.cn','xxx');  //抄送
$mail->addBCC('ggz@itsource.cn','yyyy');   //密送

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // 指定是否为html的邮件

//指定邮件的标题
$mail->Subject = 'Here is the subject';
//指定邮件的内容
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
//指定邮件的内容(当html无效时显示该内容)
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


//>>3.发送邮件
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}


//>>2.将邮件内容保存到126服务器上.


