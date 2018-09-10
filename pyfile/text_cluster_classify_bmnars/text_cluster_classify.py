# -*- coding: utf-8 -*-
"""
Created on Wed Jul 18 10:13:43 2018

@author: JenniferZhang
"""


#设置工作目录
#import os
#os.chdir('F:/复能基因/数据挖掘工作/6. text_cluster_classify_bmnars')

#解决_tkinter.TclError: no display name and no $DISPLAY environment variable的问题
#import matplotlib
#matplotlib.use('Agg')
import matplotlib.pyplot as plt
plt.switch_backend('Agg')


#导入相关库
import os
import re
import pymysql
import jieba
import difflib
import pandas as pd
import numpy as np
#import json
from cnprep import Extractor
import string #去除英文标点符号和数字
import zhon.hanzi #去除中文标点符号
from html_text import extract_text
#from simhash import Simhash,SimhashIndex
from bs4 import BeautifulSoup
from sklearn.cluster import KMeans
#from sklearn.manifold import TSNE
from sklearn.decomposition import SparsePCA
import matplotlib.pyplot as plt
from matplotlib.font_manager import FontProperties
#数据标准化库
#from sklearn.preprocessing import MinMaxScaler
from sklearn.preprocessing import MaxAbsScaler
#from sklearn.preprocessing import maxabs_scale
from sklearn.model_selection import train_test_split #模型数据分割
from sklearn.feature_extraction.text import CountVectorizer,TfidfVectorizer,TfidfTransformer #特征
from sklearn.naive_bayes import MultinomialNB #多项式朴素贝叶斯
from sklearn.linear_model import LogisticRegression #逻辑回归
from sklearn.ensemble import RandomForestClassifier #随机森林
from sklearn.svm import SVC
from sklearn.svm import LinearSVC #线性支持向量机
import seaborn as sns
from sklearn.model_selection import cross_val_score
from sklearn.metrics import silhouette_score
from sklearn.metrics import calinski_harabaz_score
from sklearn.metrics import accuracy_score,precision_score, \
                       recall_score,f1_score,cohen_kappa_score
from sklearn.metrics import confusion_matrix
from sklearn.metrics import classification_report


#数据库操作
class SQLManager(object):
    #初始化
    def __init__(self):
        self.connect()
        
    #打开数据库连接
    def connect(self):
        self.conn = pymysql.connect(host="localhost", user="bmnars", password="vi93nwYV", 
                               db="bmnars", port=3306, charset='utf8')
        #self.conn = pymysql.connect(host="localhost", user="root", password="", 
        #                       db="bmnars", port=3306, charset='utf8') #打开数据库连接
        print("数据库连接成功！")
        self.curs = self.conn.cursor() #创建一个游标对象curs
    
    #执行SQL语句
    def implement(self, sql, args=None):
        """
        :param sql: sql语句
        :param args: 其他参数，默认没有
        """
        self.curs.execute(sql, args)
        
    #查询数据
    def queryData(self, sql, args=None):
        """
        :param sql: sql语句
        :param args: 其他参数，默认没有
        :return data: 返回sql语句查询到的一条数据
        """
        self.implement(sql, args=None)
        data = self.curs.fetchone()
        return data
        
    #获取全部数据
    def selectData(self, sql, args=None):
        """
        :param sql: sql语句
        :param args: 其他参数，默认没有
        :return data: 返回sql语句查询到的全部数据
        """
        self.implement(sql, args)
        data = self.curs.fetchall()
        return data
        
    #插入数据
    def insertData(self, sql, args=None):
        """
        :param sql: sql语句
        :param args: 其他参数，默认没有
        """
        self.implement(sql, args)
        self.conn.commit() #提交
        
    #关闭数据库连接
    def close(self):
        self.curs.close()
        self.conn.close()
        
    #with定义函数
    def __enter__(self):
        return self
        
    def __exit__(self, exc_type, exc_val, exc_tb):
        self.close()
        
        
#源数据解析
class AnalyseSource(object):
    #初始化
    def __init__(self, source_url, local_url):
        """
        :param source_url: 文章url地址
        :param local_url: 文章本地存储地址
        """
        self.source_url = source_url
        self.local_url = local_url
        self.source_file_name = local_url.split("/")[-1]
        self.content_html = open(self.local_url,mode='r',encoding='utf8').read()
        soup = BeautifulSoup(self.content_html, "html.parser")
        self.content_p = {}
        i = 0 #键
        for p in soup.findAll('p'):
            self.content_p.update({i:p})
            i += 1
        
    #获取文章标题
    def getTitle(self):
        #cwca_org
        title = extract_text(''.join(re.findall('<div class="center_title">([\s\S]*?)<div class="nr">', self.content_html)))
        #ioz_ac
        if title == '':
            title = extract_text(''.join(re.findall('<table width="650".+?>([\s\S]*?)</td>', self.content_html)))
        #kepu_net
        if title == '':
            title = extract_text(''.join(re.findall('<div class="noticecaption1">(?:\[.*?\])*(.*?)</div>', self.content_html)))
        #biotech_org
        if title == '':
            title = extract_text(''.join(re.findall('id="caption".+?>(.*?)</div>', self.content_html)))
        #sciencenet
        if title == '':
            title = extract_text(''.join(re.findall('id="content1"[\s\S]*?>([\s\S]*?)</table>', self.content_html)))
        #genetics_ac
        if title == '':
            title = extract_text(''.join(re.findall('<td class="detail_title">(.*?)</td>', self.content_html)))
        #whiov_ac
        if title == '':
            title = extract_text(''.join(re.findall('<div class="xl_h">([\s\S]*?)</div>', self.content_html)))
        #ibp_cas
        if title == '':
            title = extract_text(''.join(re.findall('<td align="center" class="black_20_600">(.*?)</td>', self.content_html)))
        return title
        
    #获取文章作者
    def getAuthor(self):
        #cwca_org
        author = extract_text(''.join(re.findall('<span>作者：(.*?)\）*</span>', self.content_html)))
        #ioz_ac, genetics_ac, whiov_ac, ibp_cas
        #无作者
        #kepu_net
        if author == '':
            author = extract_text(''.join(re.findall('<strong>资料来源.+?　　[作者:：]*(.+?)</strong>', self.content_html)))
        #biotech_org
        if author == '':
            author = extract_text(''.join(re.findall('发布者：([\s\S]*?)日期', self.content_html)))
        #sciencenet
        if author == '':
            author = extract_text(''.join(re.findall('作者：(.*?)来源', self.content_html)))
        return author
        
    #获取文章来源
    def getSource(self):
        #cwca_org
        source = extract_text(''.join(re.findall('<span>来源：(.*?)</span>', self.content_html)))
        #ioz_ac
        if source == '':
            source = extract_text(''.join(re.findall('来源：(.*?)\|.+?<span id="pageview">', self.content_html)))
        #kepu_net
        if source == '':
            source = extract_text(''.join(re.findall('<strong>资料来源：(.+?)　　', self.content_html)))
        #biotech_org
        if source == '':
            source = extract_text(''.join(re.findall('来源：([\s\S]*?)发布者', self.content_html)))
        #sciencenet, whiov_ac, ibp_cas
        if source == '':
            source = extract_text(''.join(re.findall('来源：(.*?)发布时间', self.content_html)))
        #genetics_ac
        #无文章来源
        return source
        
    #获取文章发布时间
    def getSourceDate(self):
        #cwca_org
        source_date = extract_text(''.join(re.findall('<span>发布日期：([\s\S]*?)</span>', self.content_html)))
        #ioz_ac
        if source_date == '':
            source_date = extract_text(''.join(re.findall('发布日期：([\s\S]*?)\|', self.content_html)))
        #kepu_net, genetics_ac
        #无发布日期
        #biotech_org
        if source_date == '':
            source_date = extract_text(''.join(re.findall('日期：([\s\S]*?)<span id="views">', self.content_html)))
        #sciencenet
        if source_date == '':
            source_date = extract_text(''.join(re.findall('发布时间：(.*?)</div>', self.content_html)))
        #whiov_ac
        if source_date == '':
            source_date = extract_text(''.join(re.findall('发布时间：(.*?)【字号：', self.content_html)))
        #ibp_cas
        if source_date == '':
            source_date = extract_text(''.join(re.findall('<td align="center" class="font03">(.*?) | 【', self.content_html)))
        return source_date
        
    #获取文章内容
    def getContentText(self):
        #cwca_org
        content_text = extract_text(''.join(re.findall('<body>([\s\S]*?)</body>', self.content_html)))
        #ioz_ac, genetics_ac
        if content_text == '':
            content_text = extract_text(''.join(re.findall('id="zoom">([\s\S]*?)<td align="center"', self.content_html)))
        #kepu_net
        if content_text == '':
            content_text = extract_text(''.join(re.findall('<div class="noticevalue">([\s\S]*?)更多相关内容', self.content_html)))
        #biotech_org
        if content_text == '':
            content_text = extract_text(''.join(re.findall('id="nr">([\s\S]*?)<script', self.content_html)))
        #whiov_ac
        if content_text == '':
            content_text = extract_text(''.join(re.findall('id="xl_text">([\s\S]*?)<div class="fenyedisplay-1"', self.content_html)))
        #ibp_cas
        if content_text == '':
            content_text = extract_text(''.join(re.findall('id="content">([\s\S]*?)<td align="center"', self.content_html)))
        #sciencenet
        if content_text == '':
            content_text = extract_text(''.join(re.findall('</table>([\s\S]*?)</table>', self.content_html)))
        return content_text
        
    #文章整合
    def getResult(self):
        title = self.getTitle()
        author = self.getAuthor()
        source = self.getSource()
        source_date = self.getSourceDate()
        content_text = self.getContentText()
        resultList = [title,author,source,self.source_url,source_date,self.source_file_name,self.content_html,self.content_p,content_text]
        return resultList
        
        
#源数据清洗
class CleanData(object):
    #初始化
    def __init__(self, data):
        self.data = data
        #初步过滤
        ext = Extractor(args='message') #去除标点符号，提取标题正文
        self.data['title_tmp'] = self.data['title'].apply(lambda x:ext.extract(x.lower())['message'])
        #排序去重
        #data.sort_values(['title','source_date'], ascending=False, inplace=True)
        #data.drop_duplicates(subset='title', keep='first', inplace=True)
        self.data.sort_values(['title_tmp','source_date'], ascending=True, inplace=True)
        self.data.drop_duplicates(subset='title_tmp', keep='first', inplace=True)
        #丢弃指定轴title_tmp那列
        self.data.drop(['title_tmp'], axis=1, inplace=True)
        self.data.sort_index(inplace=True)

    #根据title、content_text相似度再次过滤
    def checkRepeat(self, checkKey, checkValue):
        score = self.data[checkKey].apply(lambda other:difflib.SequenceMatcher(None,checkValue,other).ratio())
        #dropIndex = score[score.index.values!=self.index][score>=0.95].index.values
        dataIndex = self.data[['index','source_date']][score>=0.95] #取出index，source_date字段的分数大于0.95的行
        #dataIndex = dataIndex[dataIndex.index.values!=index] #去除原index的行
        if len(dataIndex)>1:
            #dropIndex = np.append(dropIndex, self.index) #把index添加进去，然后一起比较日期，过滤掉比较晚的，留下时间最早的
            #dropIndex = np.concatenate((dropIndex, [index]))
            if (dataIndex['source_date'].isnull()).any(): #只要source_date字段有Null值
                nullIndex = dataIndex[dataIndex['source_date'].isnull()].sort_values(['index']).index.values
                dropIndex = dataIndex.dropna().sort_values(['source_date','index']).index.values
                for ni in nullIndex:
                    for i in range(len(dropIndex)):
                            if ni>dropIndex[i]:
                                i += 1
                            else: #ni<dropIndex[i]
                                dropIndex = np.insert(dropIndex, i, ni)
                                break
                            if len(dropIndex)==i: #当整个dropIndex遍历完时，那么ni插在dropIndex后面
                                dropIndex = np.append(dropIndex, ni)
                                break
            else: #全部都有日期的情况，就按日期排列
                dropIndex = dataIndex.sort_values(['source_date','index']).index.values
            print("%s remain index: %s, Drop index: %s" %(checkKey,dropIndex[0],dropIndex[1:].tolist()))
            self.data.drop(dropIndex[1:], inplace=True, errors='ignore')
           
    #查重
    def check(self, index, title, content_text):
        self.index = index
        print("\nIndex: ", self.index)
        self.checkRepeat('title', title)
        self.checkRepeat('content_text', content_text)
        
        
#获取去重后的数据       
def getCleanData():
    #连接数据库
    print("\n第1步：加载源数据地址...")
    with SQLManager() as db: #实例化
        #统计记录数
        """
        #聚类步骤
        f = open("/home/bmnars/pyfile/text_cluster_classify_bmnars/judge.txt", mode='a+', encoding='utf8')
        #f = open("./cluster_classify_data/judge.txt", mode='a+', encoding='utf8')
        dataNum = db.queryData(
                  '''
                  select count(*) from _cs_bmnars_link;
                  ''' )[0]
        print("_cs_bmnars_link表有%s条记录！" %dataNum)
        #加载源数据
        source_data = db.selectData(
                      '''
                      select source_url,local_url,id 
                      from _cs_bmnars_link;
                      '''
                                    )
        """
    
        #"""
        #分类步骤
        f = open("/home/bmnars/pyfile/text_cluster_classify_bmnars/judge.txt", mode='r+', encoding='utf8')
        #f = open("./cluster_classify_data/judge.txt", mode='r+', encoding='utf8')
        idNum = int(f.readlines()[-1:][0].strip())
        dataNum = db.queryData(
                    '''
                    select count(*) from _cs_bmnars_link
                    where id>%d;
                    ''' %idNum
                               )[0]
        print("_cs_bmnars_link表有%s条记录！" %dataNum)
        #加载源数据
        source_data = db.selectData(
                        '''
                        select source_url,local_url,id 
                        from _cs_bmnars_link where id>%d;
                        ''' %idNum
                                    )
        #"""
    print("_cs_bmnars_link表数据加载完成！")

    #开始解析数据
    print("\n第2步：解析源数据...")
    data = pd.DataFrame()
    #i = 1 #记录数
    for row in source_data:
        #print("第%d条记录..." %i)
        data = data.append([AnalyseSource(row[0],row[1]).getResult()], ignore_index=True) #获取网页内容
        #i += 1
    data.columns = ['title','author','source','source_url','source_date','source_file_name','content_html','content_p','content_text']
    data.loc[:,'source_date'] = pd.to_datetime(data['source_date'], format='%Y-%m-%dT%H:%M:%S')
    
    #标记id号
    idNum = row[2] #id号
    print("标记_cs_bmnars_link表的id号...")
    f.write(str(idNum) + os.linesep)
    f.close()   

    #开始处理数据
    print("\n第3步：源数据去重...")
    data.reset_index(level=0, inplace=True) #把索引号设置为一列
    CD = CleanData(data) #实例化
    
    #objs = [(str(key),Simhash(value)) for key,value in data['title'].to_dict().items()]
    #indexHash = SimhashIndex(objs, k=3)
    #print (index.bucket_size())

    #根据title、content_text相似度去重
    for index,title,content_text in data[['index','title','content_text']].values:
        #indexHash.get_near_dups(Simhash(title))
        CD.check(index, title, content_text)
    data.drop(['index'], axis=1, inplace=True) #删除索引列
    #保存数据
    data.to_csv("/home/bmnars/pyfile/text_cluster_classify_bmnars/CleanData.txt", index=False, header=True, encoding='utf8')
    #data.to_csv("./cluster_classify_data/CleanData.csv", index=False, header=True, encoding='utf8')
    return data,idNum
    
    
#将去重后的数据导入数据库
def importCleanData(cleanData):
    print("\n第4步：去重后的数据导入数据表中...")
    with SQLManager() as db: #实例化
        #"""
        #聚类步骤
        print("先清空_cs_bmnars_contents表数据...")
        #先清空数据表
        db.implement('truncate table _cs_bmnars_contents;')
        print("_cs_bmnars_contents表已清空！")
        #"""

        #将去重后的数据导入数据表中
        for i in cleanData.values: #逐条插入数据并提交到数据库
            db.insertData(
                '''
                insert into _cs_bmnars_contents
                (title,author,source,source_url,source_date,
                source_file_name,content_html,content_p,content_text) 
                values (%s,%s,%s,%s,%s,%s,%s,%s,%s)
                ''',
                tuple(map(str,i.tolist())))
    print("数据导入表成功！")
    
    
#数据加载(清洗后)
class loadData(object):
    #初始化
    def __init__(self):
        print("\n第5步：从数据库中加载数据...")
        self.db = SQLManager()
        
    #加载聚类数据
    def loadClusterData(self):
        print("聚类数据加载...")
        data = self.db.selectData(
                  '''
                  select id,content_text from _cs_bmnars_contents
                  where content_text!='';
                  '''
                      )
        print("聚类数据加载完成！")
        return pd.DataFrame(list(data), columns=['id','content_text'])
        
    #加载分类数据
    def loadClassData(self):
        print("分类数据加载...")
        dataNum = self.db.queryData('select count(*) from _cs_bmnars_contents;')[0]
        print("_cs_bmnars_contents表有%s条记录！" %dataNum)
        #加载数据
        trainData = self.db.selectData(
                    '''
                    select id,content_text,concat(
                    IFNULL(category1,''),IFNULL(category2,''),IFNULL(category3,''))
                    as category from _cs_bmnars_contents
                    where content_text!='';
                    ''' 
                        )
        print("分类数据加载完成！")
        return pd.DataFrame(list(trainData), columns=['id','content_text','category'])
        
        
#数据处理
class HandleData(object): 
    #去标点符号和数字
    def delPunDigit(self, words):
        pun_num = string.punctuation+string.digits+zhon.hanzi.punctuation
        words = list(map(lambda x:re.sub("[%s]+"%pun_num,"",x), words))
        return words
        
    #去除停用词
    def delStopWord(self, words):
        #加载停用词
        stopwords = pd.read_csv('/home/bmnars/pyfile/text_cluster_classify_bmnars/stopwords.txt', sep='\n', header=None, encoding='utf8')
        #stopwords = pd.read_csv('./cluster_classify_data/stopwords.txt', sep='\n', header=None, encoding='utf8')
        stopwords = list(stopwords.drop_duplicates()[0])
        stopwords.append(' ')
        stopwords.append('')
        words = list(filter(lambda x:x if x not in stopwords else 0, words))
        return words
        
    #分词
    def wordCutDel(self, text):
        words = list(jieba.cut(text)) #分词
        words = self.delPunDigit(words) #去中英文标点符号和数字
        words = self.delStopWord(words) #去停用词
        return words
        
    #创建文档词语列表
    def createWordsList(self, textList):
        wordsList = [] #存储每个文档去除停用词后的词语(append)
        #fullWords = [] #存储每个文档去除停用词后的词语(expend)
        for text in textList:
            words = self.wordCutDel(text)
            wordsList.append(words)
            #fullWords.extend(words)
        return wordsList#,fullWords
    
    #创建词汇表(词袋模型)       
    def createVocabulary(self, wordsList):
        vocabulary = set() #存储去除停用词后的所有词语，构成词汇表
        for word in wordsList:
            vocabulary |= set(word)
            #fullWords.extend(words)
        vocabulary = list(vocabulary)
        return vocabulary
    
    #把每个文档转换为词向量
    def setOfWords2Vec(self, vocabulary, docWord):
        wordVec = [0]*len(vocabulary) #初始化一个文档向量
        for word in docWord:
            if word in vocabulary:
                wordVec[vocabulary.index(word)] += 1
        return wordVec
    
    #聚类数据处理主程序
    def dealData(self, textList):
        #创建文档词语列表
        print("创建词语列表...")
        wordsList = self.createWordsList(textList)
        #创建词汇表
        print("创建词汇表...")
        vocabulary = self.createVocabulary(wordsList)
        #把训练数据中的每个文档转换为词向量
        print("将文档词语转换为词向量...")
        docMatrix = [] #存储所有文档向量
        for docWord in wordsList:
            docMatrix.append(self.setOfWords2Vec(vocabulary,docWord))
        print("数据处理完成！")
        return docMatrix
        
        
#聚类(KMeans)
class ClusterData(object):
    #数据标准化
    def normalData(self, docMatrix):
        #数据标准化
        scale = MaxAbsScaler().fit(docMatrix) #训练规则
        dataScale = scale.transform(docMatrix) #应用规则
        #dataScale = maxabs_scale(docMatrix)
        return dataScale
        
    #数据降维(稀疏PCA)
    def dimData(self, dim_n, docMatrix):
        #使用PCA进行数据降维,降成两维
        pca =  SparsePCA(n_components=2, random_state=144).fit_transform(docMatrix)
        df = pd.DataFrame(pca.components_) #将原始数据转换为DataFrame
        return df
        
    #模型训练
    def trainModel(self, n, docMatrix):
        dataScale = self.normalData(docMatrix)
        model = KMeans(n_clusters=n,random_state=144).fit_transform(dataScale) #构建并训练模型
        #模型预测
        #result = kmeans.predict([[1.5,1.5,1.5,1.5]])
        return model
    
    #模型评价并选择最优参数
    def evaluation(self, docMatrix):
        df = self.dimData(2, docMatrix)
        #可视化聚类结果
        font = FontProperties(fname='/home/bmnars/spider_porject/spider_venv/lib/python3.4/site-packages/matplotlib/mpl-data/fonts/ttf/WenQuanYi-Micro-Hei.ttf', size=10) #设置字体
        #font = FontProperties(fname='C:/Windows/Fonts/simhei.ttf', size=10) #设置字体
        colors = ['b','r','g','c','m','y','k','w'] #设置颜色
        markers = ['o','*','D','+','v','h','.','p'] #设置标记符号
        #轮廓系数评价方法，最佳值为畸变程度最大，不需要真实值
        #衡量一个结点与它属聚类相较于其它聚类的相似程度，取值范围-1到1，值越大表明这个结点更匹配其属聚类而不与相邻的聚类匹配。 
        #如果大多数结点都有很高的silhouette value，那么聚类适当。若许多点都有低或者负的值，说明分类过多或者过少。 
        print("轮廓系数评价方法(KMeans_silhouette最佳值为畸变程度最大,在不考虑业务情况下得分越高越好，最高得分是1)...")
        #Calinski-Harabasz指数评价方法，最佳值为相较最大，不需要真实值
        #分数越大，说明组内协方差越小，组间协方差越大，聚类效果越好，而数值越小可以理解为组间协方差很小，界限不明显
        print("Calinski-Harabasz指数评价方法(最佳值为相较最大)...")
        silhouettteScore = []
        for n in range(2,8):
            bestModel = self.trainModel(n, docMatrix) #构建并训练模型
            for i,l in enumerate(bestModel.labels_):
                plt.plot(df[0][i], df[1][i], color=colors[l], marker=markers[l])
                #plt.xlim([0,10])
                #plt.ylim([0,10])
            #轮廓系数
            ss_score = silhouette_score(docMatrix, bestModel.labels_, metric='euclidean')
            silhouettteScore.append(ss_score)
            plt.title('K=%s, 轮廓系数=%.03f' %(n,ss_score), fontproperties=font)
            #plt.show()
            plt.savefig('/home/bmnars/pyfile/text_cluster_classify_bmnars/image/KMeans_visualization_%s_test.png' %n)
            #plt.savefig('./image/KMeans_visualization_%s_test.png' %n)
            plt.cla()
            plt.close("all")
            #CH指标
            ch_score = calinski_harabaz_score(docMatrix, bestModel.labels_)
            print('K=%s, calinski_harabaz指数=%.03f' %(n,ch_score))
        #轮廓系数畸变程度
        plt.plot(range(2,8), silhouettteScore, linewidth=1.5, linestyle="-")
        plt.title('轮廓系数畸变程度', fontproperties=font)
        #plt.show()
        plt.savefig('/home/bmnars/pyfile/text_cluster_classify_bmnars/image/KMeans_silhouette_test.png')
        #plt.savefig('./image/KMeans_silhouette_test.png')
        plt.cla()
        plt.close("all")
        
    #聚类主方法
    def cluster(self, n, docMatrix):
        print("\n第6步：聚类...")
        #构建模型
        print("构建聚类模型...")
        model = self.trainModel(n, docMatrix)
        #结果可视化和模型评价
        print("可视化结果和模型评价...")
        self.evaluation(docMatrix)
        print("聚类完成！\n")
        return model.labels_
        
        
#分类(SVM)
class ClassifyData(object):
    def __int__(self, trainData, labels, testData):
        """
        :param trainData: 训练集，数据框格式，这里是data['content_text']
        :param labels: 训练集标签，这里是data['category']!=''，即有标签类
        :param testData: 训练集标签，这里是data['category']==''，即没有标签类
        """
        self.trainData = trainData
        self.labels = labels
        self.testData = testData
        print("数据探索...")
        #不平衡的类
        plt.figure(figsize=(8,6))
        self.trainData.groupby('category').content_text.count().plot.bar(ylim=0)
        #plt.show()
        plt.savefig('/home/bmnars/pyfile/text_cluster_classify_bmnars/image/original_unbalance.png')
        #plt.savefig('./image/original_unbalance.png')
        
    #词袋模型
    def TFIDF(self):
        """
        :param data: 训练集，数据框格式
        :param labels: 训练集标签
        """
        stopwords = pd.read_csv('/home/bmnars/pyfile/text_cluster_classify_bmnars/stopwords.txt', sep='\n', header=None, encoding='utf8')
        #stopwords = pd.read_csv('./cluster_classify_data/stopwords.txt', sep='\n', header=None, encoding='utf8')
        stopwords = list(stopwords.drop_duplicates()[0])
        stopwords.append(' ')
        stopwords.append('')

        tfidf = TfidfVectorizer(sublinear_tf=True, min_df=5, norm='l2', 
                                encoding='utf8', ngram_range=(1,2), stop_words=stopwords)
        features = tfidf.fit_transform(self.trainData).toarray()
        print("features.shape: ", features.shape)
        return features
        
    #数据集分割
    def SplitData(self, data, labels):
        """
        :param data: 训练集，数据框格式
        :param labels: 数据集标签
        """
        return train_test_split(data, labels, test_size=0.2, random_state=144)
        
    #数据标准化
    def NormalData(self, data):
        """
        :param data: 需要标准化的数据集
        """
        #数据标准化（可以保存训练集中的参数（均值、方差）直接使用其对象转换测试集数据）
        return MaxAbsScaler().fit(data)
        
    #多项式朴素贝叶斯
    def MNBModel(self):
        """
        :param trainData: 训练集，数据框格式，这里是data['content_text']
        :param labels: 训练集标签，这里是data['category']!=''，即有标签类
        :param testData: 训练集标签，这里是data['category']==''，即没有标签类
        """
        X_trainData, y_verifyData, X_trainClasses, y_verifyClasses = self.SplitData(self.trainData, self.labels)
        X_trainTfidf = TfidfTransformer().fit_transform(CountVectorizer().fit_transform(X_trainData))
        y_verifyCounts = CountVectorizer().fit_transform(y_verifyData)
        #y_verifyTfidf = TfidfTransformer().fit_transform(y_verifyCounts)
        testCounts = CountVectorizer().fit_transform(self.testData)
        #建立MultinomialNB模型
        MNBmodel = MultinomialNB().fit(X_trainTfidf, X_trainClasses)
        #模型验证
        return MNBmodel,y_verifyCounts,y_verifyClasses,testCounts
        
    #支持向量机
    def SVMModel(self):
        """
        :param trainData: 训练集，这里指训练文章内容向量，即trainDocMatrix
        :param labels: 训练集标签，这里指文章标签，即categories
        :param testData: 测试集，这里指测试文章内容向量，即testDocMatrix
        """
        #数据处理
        textList = []
        textList.expend(self.trainData.values)
        textList.expend(self.testData.values)
        HD = HandleData()
        docMatrix = np.array(HD.dealData(textList))
        trainDocMatrix = docMatrix[self.trainData] #训练数据
        testDocMatrix = docMatrix[self.testData] #测试数据

        #将数据划分为训练集测试集
        X_trainData, y_verifyData, X_trainClasses, y_verifyClasses = self.SplitData(trainDocMatrix, self.labels)
        X_trainScale = self.NormalData().transform(X_trainData) #应用规则
        y_verifyScale = self.NormalData().transform(y_verifyData)
        testScale = self.NormalData().transform(testDocMatrix)
        #建立SVM模型
        SVMModel = SVC().fit(X_trainScale, X_trainClasses)
        return SVMModel,y_verifyScale,y_verifyClasses,testScale
        
    #模型选择（线性支持向量机、多项式朴素贝叶斯、逻辑回归、随机森林）
    def modelCloose(self):
        #模型
        models = [
           LinearSVC(),
           MultinomialNB(),
           LogisticRegression(random_state=144),
           RandomForestClassifier(n_estimators=200, max_depth=3, random_state=144),
        ]
        #特征向量计算
        features = self.TFIDF()
        CV = 5
        cv_df = pd.DataFrame(index=range(CV*len(models)))
        entries = []
        for model in models:
            model_name = model.__class__.__name__
            #cross_val_score：交叉验证某个模型在某个训练集上的稳定性，输出k个预测精度（K折交叉验证(k-fold)）
            accuracies = cross_val_score(model, features, self.labels, scoring='accuracy', cv=CV)
            for fold_idx, accuracy in enumerate(accuracies):
               entries.append((model_name, fold_idx, accuracy))
               
        cv_df = pd.DataFrame(entries, columns=['model_name', 'fold_idx', 'accuracy'])
        sns.boxplot(x='model_name', y='accuracy', data=cv_df)
        sns.stripplot(x='model_name', y='accuracy', data=cv_df, 
                     size=8, jitter=True, edgecolor="gray", linewidth=2)
        #plt.show()
        plt.savefig('/home/bmnars/pyfile/text_cluster_classify_bmnars/image/fold_idx.png')
        #plt.savefig('./image/fold_idx.png')
        print(cv_df.groupby('model_name').accuracy.mean())
        #返回accuracy平均值最大的model_name
        return cv_df.groupby('model_name').accuracy.mean().argmax()
        
    #模型预测
    def modelPredict(self, model, varityData):
        """
        :param model: 模型，此处为上述modelCloose步骤中的最优模型
        :param varityData: 验证集
        """
        preClasses = [] #预测标签
        for v in varityData:
            preClasses.append(model.predict(v))
        return preClasses
        
    #模型评价
    def evaluation(self, verifyClasses, preClasses):
        #准确率，最佳值为1.0
        accuracy = accuracy_score(verifyClasses, preClasses, normalize=True)
        #精确率，最佳值为1.0
        precision = precision_score(verifyClasses, preClasses, average="micro")
        #召回率，最佳值为1.0
        recall = recall_score(verifyClasses, preClasses, average="micro")
        #F1值，最佳值为1.0
        f1 = f1_score(verifyClasses, preClasses, average="micro")
        #Cohen’s Kappa系数，介于(-1, 1)，最佳值为1.0，score>0.8意味着好的分类；0或更低意味着不好（实际是随机标签）
        cohen_kappa = cohen_kappa_score(verifyClasses, preClasses)
        print('使用SVM预测数据(最佳值都为1.0)：')
        print('准确率为：', accuracy)
        print('精确率为：', precision)
        print('召回率为：', recall)
        print('F1值为：', f1)
        print('Cohen’s Kappa系数为：', cohen_kappa)
        #混淆矩阵
        confusion = confusion_matrix(verifyClasses, preClasses)
        print("混淆矩阵为：\n", confusion)
        #分类报告
        report = classification_report(verifyClasses, preClasses)
        print("分类报告为：\n", report)
        
    #分类主程序
    def classify(self):
        print("\n第6步：分类...")
        #模型构建
        print("模型选择...")
        model = self.modelCloose()
        #模型分类预测
        print("模型预测...")
        preClasses = self.modelPredict(model, self.y_verifyScale)
        #模型评价
        print("模型评价...")
        self.evaluation(self.y_verifyClasses, preClasses)
        print("模型应用...")
        preTarget = self.modelPredict(model, self.testScale)
        print("分类完成！\n")
        return preTarget
        
        
#将聚类标签导入数据库中
def importLabel(result):
    print("\n第7步：结果标签导入数据库中...")
    with SQLManager() as db: #实例化
        #逐条插入数据
        for row in result.values:
            db.insertData(
                    '''
                    update _cs_bmnars_contents
                    set category%d=%d 
                    where id=%d 
                    ''' 
                    %(int(row[1]),int(row[1]),int(row[0]))
                          )
    print("聚类标签导入表成功！\n")
    
    
#聚类主方法    
def main_1():
    cmd_dir = '/home/bmnars/spider_porject/spider_venv/bin/python3'
    py_dir = '/home/bmnars/pyfile/text_cluster_classify_bmnars/sendmail.py'
    
    try:
        #源数据清洗
        #cleanData,idNum = getCleanData()
        #importCleanData(cleanData)
        #聚类数据加载
        LD = loadData()
        data = LD.loadClusterData()
        #数据处理
        textList = data['content_text'].values
        HD = HandleData()
        docMatrix = np.array(HD.dealData(textList))
        #聚类
        CD = ClusterData()
        labels = CD.cluster(3, docMatrix)
        data["category"] = labels+1
        result = data[['id','category']]
        #结果标签导入数据库
        importLabel(result)
        print("程序运行完毕！")
        os.system("%s %s %s" %(cmd_dir,py_dir,'程序运行完毕！'))
        
    except Exception as e:
        print("Exception:", e)
        os.system("%s %s %s" %(cmd_dir,py_dir,'Exception:'+str(e)))
        
        
def main_2():
    cmd_dir = '/home/bmnars/spider_porject/spider_venv/bin/python3'
    py_dir = '/home/bmnars/pyfile/text_cluster_classify_bmnars/sendmail.py'

    try:
        #源数据清洗
        cleanData,idNum = getCleanData()
        importCleanData(cleanData)
        #分类数据加载
        LD = loadData()
        data = LD.loadClassData()
        trainData = data[data['category']!=''] #训练数据
        categories = data['category'][data['category']!=''] #训练数据中的已知属性
        testData = data[data['category']==''] #测试数据
        #分类
        #CD = ClassifyData()
        #labels = CD.classify(trainDocMatrix, categories, testDocMatrix)
        CD = ClassifyData(trainData,categories,testData)
        preLabels = CD.classify()
        #增加一列label
        testData = testData.assign(category=preLabels)
        result = testData[['id','category']]
        #结果标签导入数据库
        importLabel(result)
        #程序运行完毕
        print("程序运行完毕！")
        os.system("%s %s %s" %(cmd_dir,py_dir,'程序运行完毕！'))
        
    except Exception as e:
        print("Exception:", e)
        os.system("%s %s %s" %(cmd_dir,py_dir,'Exception:'+str(e)))
        
        
if __name__ == '__main__':
    main_1()
    
    
#快速搭建http服务，Linux目录共享，浏览器查看图片，要在共享目录下执行如下命令
#python -m SimpleHTTPServer 端口号
#python3 -m http.server 端口号
#例如：
#../spider_porject/spider_venv/bin/python3 -m http.server 6789

#minute hour day month week command
#0, 12, 1, *, *, get_access_log.sh


