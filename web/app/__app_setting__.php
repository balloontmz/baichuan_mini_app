<?php
namespace  app;
/**
 * 项目的配置文件
 */
define("YZE_UPLOAD_PATH", YZE_APP_PATH. "public_html".DS."upload".DS);//end by /
define("YZE_MYSQL_USER",  "root");
define("YZE_MYSQL_HOST_M",  "127.0.0.1");
define("YZE_MYSQL_DB",  "baichuan");
define("YZE_MYSQL_PORT",  "3306");
define("YZE_MYSQL_PASS",  "weiwei");
define("YZE_MONGODB_USER",  "");
define("YZE_MONGODB_HOST_M",  "");
define("YZE_MONGODB_NAME",  "");
define("YZE_MONGODB_PORT",  "");
define("YZE_MONGODB_PASS",  "");
define("SITE_URI", "http://baichuan.localhost/");//网站地址
define("UPLOAD_SITE_URI", "http://baichuan.localhost/upload/");//上传文件内容访问地址，比如cdn; 这跟YZE_UPLOAD_PATH是对应的


define("YZE_DEVELOP_MODE",  true );
define('YZE_REWRITE_MODE', YZE_REWRITE_MODE_REWRITE);//开发时一但设置便别在修改
ini_set("display_errors","on");
ini_set('error_reporting', E_ALL & ~E_STRICT & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);       //错误级别
date_default_timezone_set('Asia/Chongqing');         //时区
define("APPLICATION_NAME", "佰川回收");               //应用名称
define("UI_FRAMEWORK_NAME", "layui");                //前端ui框架名称
define("ORG_NAME", "佰川回收");
define("ORG_FULL_NAME", "佰川回收");
define("VERSION", "1.0.0");
define("PAGE_SIZE", 10);//分页大小, 和layui table默认分页10条保持一致
define("FACE_APP_ID", "");//虹软人脸识别appid
define("FACE_SDK_KEY", "");//虹软人脸识别sdk key
define("ERP_URL", "http://yangaisso.localhost");//erp的地址
define("ERP_SSO_TOKEN", "fdlkfdlkftieoi321345ted");//与erp通讯时的token，和ERP系统系统中的SSO_TOKEN保持一致
?>
