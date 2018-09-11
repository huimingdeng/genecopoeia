# BmnarsAudit 插件开发手册 #
The BmnarsAudit plugin development manual -- by DHM(huimingdeng)
## 开发目的 ##
因“生命的奥秘”文章更新，需要获取第三方文章扩展“生命的奥秘”站点优质文章数量。特开发一款简易的审核爬虫文章工具，审核通过的爬虫文章则自动以“草稿”形式发布到 WordPress 的Posts(文章)中。

## 使用的库文件 ##
Js库： bootstrap组件 、layer组件、operation.js(插件操作脚本)

CSS库：bootstrap样式库、自定义文件


## 插件结构 ##

|-- BmnarsAudit	<br>
|---- css	<br>
|---- data	: 爬虫获取的网站的图片<br>
|------ biotech_org_spider_result 	<br>
|-------- 	img <br>
|------ cwca_org_spider_result	<br>
|-------- 	img <br>
|------ ioz_ac_spider_result	<br>
|-------- 	img <br>
|------ kepu_net_spider_result	<br>
|-------- 	img <br>
|------ sciencenet_spider_result	<br>
|-------- 	img <br>
|---- fonts : bootstrap 字体图标<br>
|---- images <br>
|---- js <br>
|---- lib : 部分js库，`eg. layer.js`<br>
|---- Service <br>
|------ bmnars-ajax.php : 异步操作对象处理文件 <br>
|---- Views <br>
|------ bmnars-list.php : 初审列表页 <br>
|------ bmnars-recheck.php : 复审列表页 <br>
|------ template.php : 爬虫文章整理后的输出模板 <br>
|---- bmnarsaudit.php 插件入口文件 <br>
|---- DevelopDoc.md 开发手册<br>
|---- help_manual.docx 使用帮助文档<br>
|---- README.md : 开发记录手册


