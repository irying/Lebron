##方法签名验证

规则：在API URL 后面带上所需签名参数与签名后的字符串。

固定参数:`sign`,`method`,`timestamp`,`platform`。

- **sign** ：生成后的签名字符串
- **method**：签名的方法，支持sha1,md5
- **timestamp** ： 当前时间戳。在服务端验证有 `60秒` 的有效期。无论是签名用还是传递过来都必须保持一致。
- **platform**： 私钥对应的平台,如 `pc`,`ios`,`android`,`web`。

**其中 `sign`,`method` 不参与签名**。

将**其余参数按字母排序**，以GET参数字符串的方式拼接，最后拼接 **私钥** ，成为一个字符串 ,用 `method` 指定的方法进行签名。

如PC端的私钥是 `46c71b66d226e3842682b3f5f69296e4` ;

检查用户手机号的API所需的签名参数是 `mobile`，签名方法是 `sha1` 。则生成签名的方法为
	
	// 私钥
	$authkey = '46c71b66d226e3842682b3f5f69296e4';
	// 生成签名
	$sign = sha1("mobile=13800138000&platform=pc&timestamp=1422241139".$authkey);

得到签名字符串 `337d550d7f29f7d93eb1428b14ff04627fa6e94e`。

则该URL为：

[http://api.ibos.cn/v1/users/checkmobile?mobile=13800138000&timestamp=1422241139&platform=pc&method=sha1&sign=337d550d7f29f7d93eb1428b14ff04627fa6e94e](http://api.ibos.cn/v1/users/checkmobile?mobile=13800138000&timestamp=1422241139&platform=pc&method=sha1&sign=337d550d7f29f7d93eb1428b14ff04627fa6e94e)