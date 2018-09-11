# BmnarsAudit [生命奥秘](http://www.lifeomics.com/) 爬虫文章审核管理插件 #

v0.0.1 创建入口文件，设计入口文件，引入样式和脚本等。

v0.0.2 修复插件地址（Plugin URI）错误

v0.0.3 bmnars-list.php 文件引入 bootstrap 样式和脚本，引入 bootstrap.dataTable 表格.

v0.0.4 创建 bmnars-ajax.php 做为数据库操作的异步访问程序。添加查询爬虫数据的SQL查询。

v0.0.5 添加预览按钮，同步到WordPress草稿箱，预览效果。
	![版本0.0.5预览示意图](https://i.imgur.com/Q20HfZM.png)

v0.0.6 取消上一版本的发布到WordPress草稿箱的功能；实现审核功能，通过，则发布到 WordPress 草稿箱。不通过，则不实习效果。

v0.0.7 添加插件激活动作 —— 创建审核状态表，用于记录审核操作。

v0.0.8 实现审核通过功能，发布到 WordPress 成为草稿，实现审核不通过，记录状态，不用发布到 WordPress。

v0.0.9 实现复审页面。仅管理员可操作。
![版本0.0.9示意图](https://i.imgur.com/zGWXwg5.png)

v1.0.0 添加限制，复审操作指定账户操作。

----------

## _cs_audit_bmnars_status 表数据字典： ##

<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th>编号</th>
			<th>字段</th>
			<th>类型长度</th>
			<th>说明</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>1</td>
			<td>id</td>
			<td>int(11)</td>
			<td>主键标识</td>
		</tr>
		<tr>
			<td>2</td>
			<td>crawler_id</td>
			<td>int(11)</td>
			<td>爬虫文章主键标识，用于记录审核状态所属文章标识</td>
		</tr>
		<tr>
			<td>3</td>
			<td>post_id</td>
			<td>int(11)</td>
			<td>爬虫文章审核通过后保存的发布成草稿的wp文章ID（post）</td>
		</tr>
		<tr>
			<td>4</td>
			<td>user_id</td>
			<td>int(11)</td>
			<td>审核用户操作的用户编号</td>
		</tr>
		<tr>
			<td>5</td>
			<td>status</td>
			<td>int(11)</td>
			<td>爬虫文章审核状态，NULL:未审核，1:审核通过，2:审核不通过。</td>
		</tr>
		<tr>
			<td>6</td>
			<td>audit_time</td>
			<td>int(11)</td>
			<td>UNIX时间戳，记录审核时间。</td>
		</tr>
	</tbody>
</table>