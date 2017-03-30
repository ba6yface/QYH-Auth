# wxqy
## 安装
1. 使用composer下载扩展包  
  `composer require decent/wechat`
2. 在项目 config/app.php 文件中的providers数组中，添加以下代码引入服务提供器  
  `Decent\Wechat\WechatServiceProvider::class`
3. 运行以下命令，将配置文件发布到config文件夹    
  `php artisan vendor:publish`  
  
## 配置  
#####  企业号id - `corp_id`  
#####  企业号secret - `corp_secret`
#####  认证回调地址 - `auth_action`  
跳转到微信登录后，扫码后会重定向到此地址，附带用户认证的code  
#####  实现提供器 - `providers`  
认证流程需要用到以下几个实现，其中session和微信认证已有默认提供器：  
1. 查找用户  
   需要提供根据企业号成员ID获取用户model的函数
2. 保存Session
   用户登录时保存session，登出时销毁session
3. 微信认证  
   认证登录页重定向回来的code，获取企业号用户ID
