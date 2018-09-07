# SearchMenuOptions 插件开发手册 #
The SearchMenuOptions plugin development manual -- by DHM(huimingdeng)

## 开发目的(Development purposes) ##
每次修改 genecopoeia 站点中搜索结果中的左侧筛选菜单，都需要程序员直接在数据库中操作，因此为了方便操作而开发的简易管理插件。
<small>(Every time the left filter menu in the search results of the genecopoeia site is modified, the programmer needs to operate directly in the database, so the simple management plug-in is developed for the convenience of operation.)</small>

## 使用的库文件 ##
Js库： bootstrap组件 、layer组件、operation.js(插件操作脚本)

CSS库：bootstrap样式库、自定义文件

## 插件结构 ##

|-- [SearchMenuOption](https://github.com/huimingdeng/genecopoeia/tree/master/SearchMenuOptions)	<br>
|---- assets	<br> 
|------ css		<br>
|-------- bootstrap.min.css	<br>
|-------- dataTables.bootstrap.css	<br>
|-------- searchmenuoptions.css	<br>
|------ fonts	<br>
|------ images	<br>
|------ js	<br>
|-------- layer-v2.3	<br>
|-------- bootstrap.min.js <br>
|-------- **... ...**	<br>
|---- classes	<br>
|------ [admin.class.php](https://github.com/huimingdeng/genecopoeia/blob/master/SearchMenuOptions/classes/admin.class.php) 	<br>
|------ [ajax.class.php](https://github.com/huimingdeng/genecopoeia/blob/master/SearchMenuOptions/classes/ajax.class.php)	<br>
|------ [input.class.php](https://github.com/huimingdeng/genecopoeia/blob/master/SearchMenuOptions/classes/input.class.php)	<br>
|------ [response.class.php](https://github.com/huimingdeng/genecopoeia/blob/master/SearchMenuOptions/classes/response.class.php)	<br>
|---- install	<br>
|------ active.php 	<br>
|------ deactive.php	<br>
|---- languages	<br>
|---- views	<br>
|------ addOne.win.php	<br>
|------ delOne.win.php	<br>
|------ editOne.win.php	<br>
|------ list.php <br>
|------ listOne.php <br>
|------ search-main.page.php	<br>
|------ search-test.page.php	<br>
|---- DevelopDoc.md	<br>
|---- help manual.docx	<br>
|---- README.md	<br>
|---- [searchmenuoptions.php](https://github.com/huimingdeng/genecopoeia/blob/master/SearchMenuOptions/searchmenuoptions.php)


## 功能介绍(部分简介) ##

1. **searchmenuoptions.php** -- 入口文件类，加载 classes 目录和 install 目录中的资源。
2. **admin.class.php** -- 管理页面引入类，引入插件主页面。
3. **input.class.php** -- 获取用户提交数据的方法，过滤非法字符等。
4. **ajax.class.php** -- 异步执行方法的操作类，对应 operation.js 脚本操作。
5. **response.class.php** 用来返回 ajax.class.php 中的结果。
6. ****.win.php*** -- 表示弹窗
7. ****.page.php*** -- 表示页面
8. **list.php** 和 **listOne.php** 为所有结果的表格模板和一个类别结果的表格模板。
9. ***... ...***