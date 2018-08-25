# -*- encoding:utf-8 -*-

import re
import time
import requests
import pymysql
from lxml import etree
from requests import ConnectionError


def index_page(page, judge, judge_name, url):
    """
    爬取索引页
    :param page:页码
    :param judge: 用于判断上次爬取位置
    :param judge_name: 判断爬取位置的数据保存名
    :param url: 爬取相关分类下的索引url
    """

    print('正在爬取第' + str(page) + '页索引页————>\t' + judge_name)
    full_url = url + str(page)
    # 获取索引页
    headers = {'user-agent':'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; Zune 4.0; InfoPath.3; MS-RTC LM 8; .NET4.0C; .NET4.0E)'}
    try:
        response = requests.get(full_url, headers = headers)
        response.encoding = 'utf-8'
    except ConnectionError:
        print('index_page_ConnectionError:' + url)

    # 通过xpath获取索引页内的文章列表
    index_html = etree.HTML(response.text)
    index_source = index_html.xpath('//div[@id="zuob"]//li//a/@href')

    # 写入当前爬取到的第一个文章url
    if page == 1:
        next_judge = index_source[0]
        
        with open('./' + judge_name, 'w', encoding = 'utf-8') as f:
            print(judge_name + "\t<————next_judge————>\t" + next_judge)
            f.write(next_judge)

    for item in index_source:
        # item:/information/155535
        # 判断是否爬取到上次爬取位置，是的话返回1
        if item == judge:
            print("已爬取到上次爬取位置！————>\t" + judge_name)
            return 1
            break

        get_page(item)

def get_page(url):
    """
    提取文章内容
    :param url:文章链接
    """
    # 组合url
    full_url = 'http://www.biotech.org.cn' + url
    # 获取文章内容
    headers = {'user-agent':'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)'}

    article_response = requests.get(full_url, headers = headers)
    article_response.encoding = 'utf-8'
    time.sleep(1)

    # 通过正则表达式获取文章中需要的内容
    article_pattern = re.compile(r'<div id="zuob">.*.lightBox().*?</script>', re.S)

    article_result = article_pattern.search(article_response.text)
    if article_result:
        article_source = article_result.group()

        # 提取url中的数字作为文件名保存:'/information/155535'|155535
        filename_pattren = re.compile(r'\d+')
        filename = filename_pattren.search(url)

        # 获取文章中所有的图片url链接:'http://159.226.80.12:80/imnews/NewsDataAction?Index=showImg&file=20180808141922.jpg'
        img_pattern = re.compile(r'<img(.*?)\ssrc="(.*?)"', re.S)
        img_findall = img_pattern.findall(article_source)
        for kw in img_findall:
            img_sour_pattern = re.compile(r'http')
            img_judge = img_sour_pattern.search(kw[1])
            if img_judge is None:
                img_full_url = 'http://www.biotech.org.cn'  + kw[1]
            else:
                img_full_url = kw[1]
            
            try:
                # 获取图片
                # img_save_name_pattren = re.compile(r'.*=(\d+..*)')
                img_save_name_pattren = re.compile(r'.*=(\w+.*?\.\w+)')
                img_save_name = img_save_name_pattren.search(img_full_url)               
                img_response = requests.get(img_full_url, headers = headers).content
                # 保存图片
                save_img(img_response, img_save_name)
            except ConnectionError:
                print('图片网址有误:' + img_full_url)

        def img_url_name(match):
            """
            匹配文章内容中的图片url，替换为本地url
            """
            # http://159.226.80.12/imnews/NewsDataAction?Index=showImg&file=20180814142831.jpeg
            img_real_pattern = re.compile(r'.[pjb][pnm]')
            img_real_name = img_real_pattern.search(match.group())

            if img_real_name is not None:
                img_sub_pattern = re.compile('.*=(\w+.*?\.\w+)')
                img_sub_name = img_sub_pattern.search(match.group())
                img_name  = ' src="/home/bmnars/data/biotech_org_spider_result/img/' + img_sub_name.group(1) + '"'

                return img_name

        # 匹配文章内容中的图片url，替换为本地图片url
        img_real_pattern = re.compile('\ssrc="(.*?)"')
        real_result = img_real_pattern.sub(img_url_name, article_source)

        # 保存文章内容 
        save_page(real_result, filename.group())
        save_mysql(full_url, filename.group())
    else:
        print('get_page_error:' + full_url)

def save_img(result, filename):
    """
    保存文章中的图片
    :param result: 图片文件
    :param filename: 保存的图片名
    """
    img_save_full_name = '/home/bmnars/data/biotech_org_spider_result/img/' + filename 
    with open(img_save_full_name, 'wb') as f:
        f.write(result)  
        
def save_page(html,filename):
    """
    保存到文件
    :param html: 结果
    """
    with open('/home/bmnars/data/biotech_org_spider_result/' + filename + '.html', 'w', encoding = 'utf-8') as f:
        f.write(html)

def save_mysql(source_url, local_url):
    """
    保存到文件
    :param source_url: 文章来源url
    :param local_url: 文章来源url
    """
    db = pymysql.connect(host='localhost', user='bmnars', password='vi93nwYV', port=3306, db='bmnars')
    cursor = db.cursor()
    full_local_url = '/home/bmnars/data/biotech_org_spider_result/' + local_url + '.html' 
    update_time = time.strftime('%Y-%m-%d',time.localtime())
    data = {
        'source_url':source_url,
        'local_url':full_local_url,
        'source':'www.biotech.org.cn',
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
    print("biotech_org_spider爬取开始！")
    print(time.strftime('%Y-%m-%d %H:%M:%S',time.localtime()))
    # 用for循环遍历爬取相关分类下的文章
    for wd in range(6):
        # 重点关注
        if wd == 0:
            judge_name = 'judge_6.txt'
            url = 'http://www.biotech.org.cn/about/6/page/'    
        # 医药生物
        if wd == 1:
            judge_name = 'judge_106.txt'
            url = 'http://www.biotech.org.cn/topic/106/page/'
        # 农业生物
        if wd == 2:
            judge_name = 'judge_107.txt'
            url = 'http://www.biotech.org.cn/topic/107/page/'
        # 工业生物
        if wd == 3:
            judge_name = 'judge_108.txt'
            url = 'http://www.biotech.org.cn/topic/108/page/'
        # 基础与前沿
        if wd == 4:
            judge_name = 'judge_109.txt'
            url = 'http://www.biotech.org.cn/topic/109/page/'
            # 文献导读
        if wd == 5:
            judge_name = 'judge_110.txt'
            url = 'http://www.biotech.org.cn/topic/110/page/'
    
        # 读取上次爬取时保存的用于判断爬取位置的字符串
        with open('./' + judge_name, 'r', encoding = 'utf-8') as f:
                judge = f.read()

        for i in range(1,40):
            params = index_page(i, judge, judge_name, url)
            if params == 1:
                break
            print('保存第', str(i), '页索引页所有文章成功————>\t' + judge_name)  

    print("biotech_org_spider爬取完毕，脚本退出！")
    print(time.strftime('%Y-%m-%d %H:%M:%S',time.localtime()))

if __name__ == '__main__':
    main()
