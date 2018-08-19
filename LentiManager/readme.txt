Version Description
0.1.0 新加版本，只有lenti-list.php/和入口文件
0.1.1 引入 lib/ 目录，lenti-list.php 引入相关操作按钮
0.1.2 引入 layer 插件，优化页面
0.2.0 修改入口文件 lentiManager.php 添加了插件激活和禁用操作；激活插件创建_cs_lenti_collection_delete_back备份表和创建触发器_cs_lenti_collection_trigger，插件禁用则删除备份表和删除触发器
0.2.1 修改入口文件 lentiManager.php 删除触发器的创建
0.2.2 新增 添加/修改模态框，用于对lenti数据的修改。
0.2.3 lenti-ajax.php 实现添加单条记录
0.2.4 lenti-ajax.php 实现单条记录修改
0.2.5 添加删除模态框 lenti-list.php 
0.2.6 lenti-ajax.php 实现删除数据，备份数据到 _cs_lenti_collection_delete_back 
0.3.0 新增回收站页 lenti-recycle-bin.php, lentiManager.php 入口页新增引入页面程序
0.3.1 新增批量恢复数据 lenti-ajax.php 
0.3.2 修改原来禁用插件后删除 _cs_lenti_collection_delete_back 表，现变更为卸载插件后删除 _cs_lenti_collection_delete_back 表
0.3.3 修复批量恢复数据中无法单个恢复的bug
0.3.4 lentiManager.php 入口文件中激活插件部分添加，权限管理操作的 option 值，卸载部分，添加删除 option 值
0.3.5 lenti-list.php 添加权限管理，只有分配权限用户可以进行相应的操作
0.3.6 添加权限管理页 lenti-assign.php 
0.4.0 lenti-list.php 添加文件上传功能。
0.4.1 lenti-ajax.php 批量功能传入功能。
0.4.2 修改回收站，能存储多条货号相同的产品。
0.4.3 修改回收站列表，可以显示相同货号产品的删除时间，判断最新产品。
0.5.0 添加上传文件管理页面。
0.5.1 编写文件操作类。
0.5.2 实现文件异步操作管理。
0.5.3 入口文件，创建备份表。
0.5.4 添加批量导入，先备份数据功能。
0.5.5 添加备份还原功能。
0.5.6 修改旧版本批量导入功能（Insert ... VALUES(),...,();）循环插入或修改数据。
0.5.7 增加操作日志和错误日志。
0.5.8 插件添加日志查看菜单。 
0.5.9 添加操作说明文档。