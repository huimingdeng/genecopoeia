# BmnarsAudit [生命奥秘](http://www.lifeomics.com/) 爬虫文章审核管理插件 #

v0.0.1 创建入口文件，设计入口文件，引入样式和脚本等。

v0.0.2 修复插件地址（Plugin URI）错误

v0.0.3 bmnars-list.php 文件引入 bootstrap 样式和脚本，引入 bootstrap.dataTable 表格.

v0.0.4 创建 bmnars-ajax.php 做为数据库操作的异步访问程序。添加查询爬虫数据的SQL查询。

v0.0.5 添加预览按钮，同步到WordPress草稿箱，预览效果。
	![版本0.0.5预览示意图](https://i.imgur.com/Q20HfZM.png)

v0.0.6 取消上一版本的发布到WordPress草稿箱的功能；实现审核功能，通过，则发布到 WordPress 草稿箱。不通过，则不实习效果。

v0.0.7 添加插件激活动作 —— 创建审核状态表，用于记录审核操作。