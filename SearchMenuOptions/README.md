# SearchMenuOptions plugin #
genecopoeia 网站搜索页面的克隆产品筛选菜单管理插件。（尝试插件开发使用面向对象，摈弃前面面向过程的形式）

version description：

v0.0.1 设计版本：设计页面结构，1.直接管理数据表 or 2.界面化操作按产品种类划分。

v0.0.2 完成插件入口类设计，加载插件激活钩子等操作，定义公用方法。

v0.0.3 设计管理界面，查询数据。

v0.0.4 实现 ajax 操作动作方法，返回json、HTML等结果。

v0.0.5 实现菜单的单个分页界面——每点击导航，返回对应菜单名(menu_name)的数据。

v0.0.6 实现添加功能，设计编辑页面，设计修改与删除按钮和方法。

v0.0.7 实现修改功能，输出修改弹窗。

v0.0.8 实现删除功能，输出删除确认弹窗。

v0.0.9 修复layer插件无法使用的问题。

v1.0.0 正式使用 Test 页面作为最终版本，可降低访问量，提高速度，当前为可使用的发布版本。

v1.0.1 修改页面权限，必须是 WordPress 的管理员权限才可以进入页面。


----------

Digital dictionary description（管理表的数字字典）：
<table class="table table-hover table-striped table-condensed" cellpadding="0" cellspacing="0" >
<thead>
<tr><th>编号</th><th>字段</th><th>类型长度</th><th>说明</th></tr>
</thead>
<tbody>
<tr><td>1</td><td>sn</td><td>int(10)</td><td>主键编号，用于前端访问数据表，获取对应菜单值。</td></tr>
<tr><td>2</td><td>menu_name</td><td>varchar(255)</td><td>搜索所属菜单，如 search3 则是search3的搜索筛选菜单。</td></tr>
<tr><td>3</td><td>classify_name</td><td>varchar(255)</td><td>菜单类名</td></tr>
<tr><td>4</td><td>classify_order</td><td>int(8)</td><td>菜单类排序</td></tr>
<tr><td>5</td><td>item_name</td><td>varchar(255)</td><td>菜单所属的子项，如 Product,Format 等</td></tr>
<tr><td>6</td><td>item_display_name</td><td>varchar(255)</td><td>菜单筛选的显示值</td></tr>
<tr><td>7</td><td>item_value</td><td>varchar(255)</td><td>菜单筛选的作用值</td></tr>
<tr><td>8</td><td>item_order</td><td>int(8)</td><td>菜单项排序值</td></tr>
<tr><td>9</td><td>compare_mode</td><td>varchar(255)</td><td>程序中的compare函数比较条件.</td></tr>
</tbody>
</table> 