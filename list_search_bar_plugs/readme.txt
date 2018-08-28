version 
... ...
1.3.4 : 新增 $atts 设置 search bar 的显示宽度和文字设置
1.3.5 : 修该了entrance.js.php文件中调用 list_search_bar_plugs_admin_ajax.php 的文件名
1.3.6 : 修复了当存在多个search bar情况，删除序号小于最高序号的配置，导致最高序号变小的错误(list_search_bar_plugs_service.php 操作中存在的错误) p.s. 存在多个，序号只能增加，不能减小
1.3.7 : 修改插件按钮底部间距(list_search_bar_plugs_style.css)，修改了插件布局宽度，由col-md-8 和col-md-4变成col-md-10,col-md-2(list_search_bar_plugs_admin_ajax.php)
1.3.8 : list_search_bar_tmplate.php 的 input 框 添加属性 autocomplete="off"