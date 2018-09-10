# -*- coding: utf-8 -*-
"""
Created on Thu Sep  6 09:32:39 2018

@author: JenniferZhang
"""

import os 

Test = 'This is a test!'
try:
    pprint.pprint(Test)
except Exception as e:
    print("Exception:", e)
    cmd_dir = '/home/bmnars/spider_porject/spider_venv/bin/python3'
    py_dir = '/home/bmnars/pyfile/text_cluster_classify_bmnars/sendmail.py'
    #os.system("%s %s %s" %(cmd_dir,py_dir,e))




