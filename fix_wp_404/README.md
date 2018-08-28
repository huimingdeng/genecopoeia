# fix_wp_404 工具 #
用于检测 WordPress 文章(posts)中的链接是否存在404错误，并修复文章中的404链接。当前工具使用方法如下：
命令行下运行脚本即可，不需要带参数：`php xxx.php`

## 各文件功能说明 ##
- check_404.php 检查wordpress中所有文章的连接状态（在命令行中运行此脚本）<br>
1、从problem_post.php加载需要检查的文章id <br>
2、如果没有文章id，则从数据库wp_posts中查出所有的文章id（所有状态或只是已发布状态的id）<br>
3、文章是否检查过，已检查过则跳过，否则进行下一步<br>
4、获取文章内容<br>
5、检查内容中的所有链接<br>
6、记录链接的http状态<br>
7、记录日志<br>
8、记录文章检查状态

- fix_404.php 修复wordpress中文章的404状态的链接（将修复从problem_post.php或数据库中查出的文章；在命令行中运行此脚本）<br>
1、从problem_post.php加载需要检查的文章id <br>
2、如果没有文章id，则从数据库wp_posts中查出所有的文章id（所有状态或只是已发布状态的id） <br>
3、获取数据库中的文章内容 <br>
4、修复文章中的404链接（删除链接，保留文字） <br>
5、更新文章到数据库中

- fix_404_post_from_file.php 修复wordpress中文章的404状态的链接（在命令行中运行此脚本） <br>
1、此脚本运行前，需要先运行check_404.php<br>
2、从problem_post.php加载需要检查的文章id<br>
3、如果没有文章id，则从数据库wp_posts中查出所有的文章id（所有状态或只是已发布状态的id）<br>
4、在检查后的目录check/after/中找到此文章的修复后数据，并读取此html文件内容<br>
5、把读取到的html文件内容，更新到数据库wp_posts中<br>

- verify.php 验证修复文章404前、后的内容变化（此脚本现在已没什么用） <br>
1、修复文章前请把wp_posts表复制一份，名为wp_posts_copy <br>
2、修复后请使用此脚本验证，验证前、后生成的文件放在verify/before/，verify/after/中，每篇文章生成一个html文件，以文章id命名

- config.php 配置页
- functions.php 函数页
- filter_log.php 把生成的错误日志分隔成3种类型的数据文件（图片类、链接类、pdf类）
- simple_html_dom.php 工具类
- problem_post.php 把某些需要检查/修复的文章id放在此文件的数组中
- 目录check/ ：下有before、after目录，是保存检查前、后的文章内容html数据，每篇文章已文章id命名
- 目录logs/ <br>
1、error_message.log ：错误记录日志<br>
2、post_checked.log ：已检查过的文章id（json格式）<br>
3、url_status_code.log ：已检查过的链接状态（json格式）<br>


## 在浏览器界面使用的小工具 ##

- check_status_code.php ：检查链接状态。（配合check_status_code_do.php使用）

- mutil_transfer_postid_to_permalink.php ：批量把连接从post_id形式转换到固定连接形式

- mutil_transfer_permalink_to_postid.php ：批量把连接从固定连接形式转换到post_id形式







		







