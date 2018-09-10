# -*- coding: utf-8 -*-
"""
Created on Thu Sep  6 09:32:39 2018

@author: JenniferZhang
"""

#==============================================================================
# 发送错误日志邮件
#==============================================================================
#导入相关库
import sys
import smtplib
from email.mime.text import MIMEText
from email.utils import formataddr
from datetime import datetime

#Send the mail
class SendMail(object):
    #初始化
    def __init__(self, content):
        self.Server = "localhost" #服务器
        self.Sender = '192.168.8.4' #发件人
        self.Receiver = 'junfangzhang@fulengen.com' #收件人
        self.Subject = 'Crawler Error' #邮件主题
        self.Date = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        self.Content = content #邮件正文
        self.Text = """
From: bmnars@%s
To: %s
Date: %s
Subject: %s

%s
        """ % (self.Sender, self.Receiver, self.Date, self.Subject, self.Content) #邮件内容
        
    #开始发送邮件
    def start(self):
        try:
            ret = True
            #邮件设置
            msg = MIMEText(self.Text, 'plain', 'utf8')
            msg['From'] = formataddr(['bmnars', self.Sender]) #发件人邮箱昵称、发件人邮箱账号
            msg['To'] = formataddr(['Jennifer', self.Receiver]) #收件人邮箱昵称、收件人邮箱账号
            msg['Subject'] = '爬虫错误日志' #邮件的主题，也可以说是标题
            
            server = smtplib.SMTP(self.Server)
            server.sendmail(self.Sender, self.Receiver, msg.as_string())
            server.quit()
            print("邮件发送成功！")
            return ret
        except Exception as e:
            print("错误：", e)
            print("邮件发送失败！")
            
            
#发送邮件主程序           
def main():
    SendMail(sys.argv[1:]).start()
    
      
if __name__ == '__main__':
    main()

