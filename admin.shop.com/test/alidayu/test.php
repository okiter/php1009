<?php
	include "TopSdk.php";
	date_default_timezone_set('Asia/Shanghai');


	$c = new TopClient;
	//设置应用的APP_KEY
	$c->appkey = '23268328';
	//安全码
	$c->secretKey = 'a7bd32620043c504d76564c8badcc1ad';
	//发送短信的对象
	$req = new AlibabaAliqinFcSmsNumSendRequest;
	//$req->setExtend("123456");
	//短信类型. 必须要修改
	$req->setSmsType("normal");

	//短信签名
	$req->setSmsFreeSignName("注册验证");
	//为短信模板中的变量赋值
	$req->setSmsParam('{"code":"我爱你","product":"京西商城"}');
	//接收短信的手机号
	$req->setRecNum("15183833775");
	//短信模板的编号
	$req->setSmsTemplateCode("SMS_2245271");  //   验证码${code}，您正在注册成为${product}用户，感谢您的支持！

 	//发送短信
	$resp = $c->execute($req);

?>