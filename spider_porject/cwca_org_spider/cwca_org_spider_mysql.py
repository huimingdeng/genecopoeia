# -*- encoding:utf-8 -*-

import re
import time
import requests
import pymysql
from lxml import etree
from requests import ConnectionError


def index_page(page, judge):
    """
    爬取索引页
    :param page:页码
    :param judge: 用于判断上次爬取位置
    """
    if page == 0:
        url = 'http://www.cwca.org.cn/news/tidings/index.html'
    elif page < 5:
        url = 'http://www.cwca.org.cn/news/tidings/index_' + str(page) + '.html'
    else:
        url = 'http://www.cwca.org.cn/column.column.do?m=index&columnId=402883ef405147ed014051759803002a&page=' + str(page)
    print('正在爬取第' + str(page+1) + '页索引页')
    # print(url)
    # 获取索引页
    headers = {'user-agent':'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; Zune 4.0; InfoPath.3; MS-RTC LM 8; .NET4.0C; .NET4.0E)'}
    try:
        response = requests.get(url, headers = headers)
        response.encoding = 'utf-8'
        time.sleep(1)
    except ConnectionError:
        print('index_page_ConnectionError:' + url)

    # 通过xpath获取索引页内的文章列表
    index_html = etree.HTML(response.text)
    index_source = index_html.xpath('//div[@class="listR"]//li/a')

    # 写入当前爬取到的第一个文章url:'/news/tidings/ff80808161da671a0163d9a89daa03b7.html'
    if page == 0:
        next_judge = index_source[0].xpath('@href')[0]
        with open('./judge.txt', 'w', encoding = 'utf-8') as f:
            print("next_judge:\t" + next_judge)
            f.write(next_judge)

    for item in index_source:
        # 获取索引页内所有文章的标题
        # index_title = item.text
        # 获取索引页内所有文章的url:'/news/tidings/ff80808161da671a0163d9a89daa03b7.html'
        index_url = item.xpath('@href')[0]
        # 判断是否爬取到上次爬取位置，是的话返回1
        if index_url == judge:
            print("已爬取到上次爬取位置！")
            return 1
            break

        get_page(index_url)

def get_page(url):
    """
    提取文章内容
    :param url:文章链接
    """
    # 组合url:'http://www.cwca.org.cn/news/tidings/ff80808161da671a0163d9a89daa03b7.html'
    full_url = 'http://www.cwca.org.cn' + url
    # print(full_url)
    # 获取文章内容
    headers = {'user-agent':'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)'}

    article_response = requests.get(full_url, headers = headers)
    # print( article_response.encoding, article_response.apparent_encoding)
    article_response.encoding = 'GB2312'
    time.sleep(1)

    # 通过正则表达式获取文章中需要的内容
    article_pattern = re.compile(r'<div class="listR">.*<div class="clear">', re.S)

    article_result = article_pattern.search(article_response.text)
    if article_result:
        article_source = article_result.group()

        # 提取url中的ff80808161da671a0163d9a89daa03b7.html作为文件名保存:'/news/tidings/ff80808161da671a0163d9a89daa03b7.html'
        filename_pattren = re.compile(r'/tidings/(.*)')
        filename = filename_pattren.search(url)

        # 完整图片url:'http://www.cwca.org.cn/ckeditor/userfiles/images/20180608-2.jpg'
        # img_name_pattern = re.compile(r'(.*?)')
        # img_url = img_url_pattern.search(url)

        # 获取文章中所有的图片url链接:'/ckeditor/userfiles/images/20180608-2.jpg'
        # img_pattern = re.compile(r'<img style(.*?)src="(.*?)"', re.S)
        img_pattern = re.compile(r'<img(.*?)\ssrc="(.*?)"', re.S)
        img_findall = img_pattern.findall(article_source)
        for kw in img_findall:
            # 判断提取的url是否需要组合
            img_sour_pattern = re.compile(r'http')
            img_judge = img_sour_pattern.search(kw[1])
            if img_judge is None:
                img_full_url = 'http://www.cwca.org.cn'  + kw[1]
            else:
                img_full_url = kw[1]
            # print(kw[1])
            # print('test')
            # img_full_url = 'http://www.cwca.org.cn'  + kw
            # img_url_pattern = re.compile(r'[a-zA-Z:/\.\-\_]')
            # img_url_detail = img_url_pattern.sub('', match.group(2))
            img_name_pattern = re.compile('.*\/(\w+.*?\.\w+)', re.I)
            # print('img_full_url:'+img_full_url)
            img_save_name = img_name_pattern.search(kw[1]).group(1)

            # print('img_save_name:'+ img_save_name)
            try:
                # 获取图片
                img_response = requests.get(img_full_url, headers = headers).content

                # 保存图片
                save_img(img_response, img_save_name)
            except ConnectionError:
                print('图片网址有误:' + img_full_url)

        def img_url_name(match):
            """
            匹配文章内容中的图片url，替换为本地url
            """
            # /ckeditor/userfiles/images/20180608-2.jpg
            img_real_pattern = re.compile(r'.[pj][pn]')
            img_real_name = img_real_pattern.search(match.group())
            # print('img_real:' + img_real)
            if img_real_name is not None:
                img_sub_pattern = re.compile('.*\/(\w+.*?\.\w+)')
                img_sub_name = img_sub_pattern.search(match.group())
                # img_name  = ' src="./img/' + img_sub_name.group(1) + '"'
                img_name  = ' src="/home/bmnars/data/cwca_org_spider_result/img/' + img_sub_name.group(1) + '"'
                # img_sub = img_pattern.sub(img_name, match)
                # print('img_name:'+img_name)
                return img_name

        # 匹配文章内容中的图片url，替换为本地图片url
        # img_pattern = re.compile(r'<img(.*?)\ssrc="(.*?)"', re.S)
        img_real_pattern = re.compile('\ssrc="(.*?)"')
      
        real_result = img_real_pattern.sub(img_url_name, article_source)

        # 保存文章内容 
        save_page(real_result, filename.group(1))
        save_mysql(full_url,filename.group(1))
    else:
        print('正则匹配错误error:' + full_url)

def save_img(result, filename):
    """
    保存文章中的图片
    :param result: 图片文件
    :param filename: 保存的图片名
    """
    img_save_full_name = '/home/bmnars/data/cwca_org_spider_result/img/' + filename 
    try:
        with open(img_save_full_name, 'wb') as f:
            f.write(result)  
    except:
        print('图片保存错误：' + filename)

def save_page(html,filename):
    """
    保存到文件
    :param html: 结果
    """
    with open('/home/bmnars/data/cwca_org_spider_result/' + filename, 'w', encoding = 'utf-8') as f:
        f.write(html)

def save_mysql(source_url, local_url):
    """
    保存到文件
    :param source_url: 文章来源url
    :param local_url: 文章来源url
    :param cursor: mysql游标
    """
    db = pymysql.connect(host='localhost', user='bmnars', password='vi93nwYV', port=3306, db='bmnars')
    cursor = db.cursor()
    full_local_url = '/home/bmnars/data/cwca_org_spider_result/' + local_url 
    update_time = time.strftime('%Y-%m-%d',time.localtime())

    data = {
        'source_url':source_url,
        'local_url':full_local_url,
        'source':'http://www.cwca.org.cn',
	'update_time':update_time
    }
    table = '_cs_bmnars_link'
    keys = ','.join(data.keys())
    values = ','.join(['%s']*len(data))
    sql = 'INSERT INTO {table}({keys}) VALUES ({values});'.format(table=table, keys=keys, values=values)
    #print(sql)
    try:
        if cursor.execute(sql,tuple((data.values()))):
            db.commit()
    except:
        print("save_mysql_failed:" + source_url)
        db.rollback()
    
    finally:
        cursor.close()      
        db.close()

def main():
    """
    遍历每一页索引页
    """
    print("cwca_org_spider爬取开始！")
    print(time.strftime('%Y-%m-%d %H:%M:%S',time.localtime()))
    # 读取上次爬取时保存的用于判断爬取位置的字符串
    with open('./judge.txt', 'r', encoding = 'utf-8') as f:
        judge = f.read()
    for i in range(12):
        params = index_page(i, judge)
        print('保存第', str(i+1), '页索引页所有文章成功')  
        if params == 1:
            break

    print("cwca_org_spider爬取完毕，脚本退出！")
    print(time.strftime('%Y-%m-%d %H:%M:%S',time.localtime()))

if __name__ == '__main__':
    main()
