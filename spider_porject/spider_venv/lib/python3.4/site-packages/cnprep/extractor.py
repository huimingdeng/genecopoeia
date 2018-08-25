#!/usr/bin/python
# -*-coding:utf-8-*-

"""
basic class
"""

import re

from .zh2num import get_number

class Extractor(object):
    """
    1. default delete the punctuation
    2. extract e-mail, telephone number, web address, etc.
    3. the info extracted is keeped if delete is false (default)

    init:
        option: ['xxx', 'yyy'] or 'xxx, yyy'

    input:
        one line string

    output:
        (string, {info})
    """

    def __init__(self, args=[], limit=4):
        """
        args: option
            e.g. ['email', 'url']
        limit: parameter for get_number (blur)
        """
        self._limit = limit
        self.m = ''
        self._email = []
        self._telephone = []
        self._QQ = []
        self._wechat = []
        self._url = []
        self._emoji = []
        self._tex = []
        self._blur = []

        if isinstance(args, list):
            self.option = args
        elif isinstance(args, str):
            self.option = [x.strip() for x in args.split(',')]
        else:
            self.option = []
            print('Input error. Only delete the punctuation.')

    def reset_param(self, args=[], limit=4):
        self._limit = limit
        if isinstance(args, list):
            self.option = args
        elif isinstance(args, str):
            self.option = [x.strip() for x in args.split(',')]
        else:
            self.option = []
            print('Input error. Only delete the punctuation.')

    def _get_result(self):
        """
        get the result
        """
        info = {}

        self.options2attr = {
            'email': self._email,
            'telephone': self._telephone,
            'QQ' : self._QQ,
            'wechat': self._wechat,
            'url': self._url,
            'emoji': self._emoji,
            'tex': self._tex,
            'blur': self._blur,
            'message': self.m,
        }

        for item in self.option:
            info[item] = self.options2attr[item]
        return info

    def _clear(self):
        """
        clear attr
        """
        self._email = []
        self._telephone = []
        self._QQ = []
        self._wechat = []
        self._url = []
        self._emoji = []
        self._tex = []
        self._blur = []

    def extract(self, m):
        """
        extract info specified in option
        """
        self._clear()
        self.m = m
        # self._preprocess()

        if self.option != []:
            self._url_filter()
            self._email_filter()
            if 'tex' in self.option:
                self._tex_filter()
            # if 'email' in self.option:
            #     self._email_filter()
            if 'telephone' in self.option:
                self._telephone_filter()
            if 'QQ' in self.option:
                self._QQ_filter()
            if 'emoji' in self.option:
                self._emoji_filter()
            if 'wechat' in self.option:
                self._wechat_filter()
        self._filter()
        if 'blur' in self.option:
            self._blur = get_number(self.m, self._limit)

        return self._get_result()

    def _filter(self):
        """
        delete the punctuation
        """
        pattern = u"[\s+\.\!\-\/_,$%^*(+\"\']+|[+——！】【，。？?:、：~@#￥%……&*“”（）]+"
        self.m = re.sub(pattern, "", self.m)

    # def _preprocess(self):
    #     """
    #     if the input string is str, try to convert it to unicode
    #     """
    #     if not isinstance(self.m, unicode):
    #         try:
    #             self.m = unicode(self.m, 'utf-8')
    #         except e:
    #             print('Convert to unicode raise error: ' + e)

    def _email_filter(self):
        self._email = re.findall(r'[\w\.-]+@+[\w\.-]+\.[\w\.-]+', self.m, re.A)
        if self._email != []:
            for item in self._email:
                self.m = re.sub(item, '', self.m)
        # @ => at
        others = re.findall(r'[\w\.-]+.?at.?[\w\.-]+\.[\w\.-]+', self.m, re.I | re.A)
        if others != []:
            self._email.extend(others)
            for item in others:
                self.m = re.sub(item, '', self.m)

        # . => dot && @ => dot
        others = re.findall(r'[\w\.-]+.?at.?[\w\.-]+.?dot.?[\w\.-]+', self.m, re.I | re.A)
        if others != []:
            self._email.extend(others)
            for item in others:
                self.m = re.sub(item, '', self.m)

                # print(item)
        # for i in range(len(others)):
        #     others[i] = re.sub(r'.?at.?', '@', others[i])
        # # @ => @@
        # others = re.findall(r'[\w\.-]+@@[\w\.-]+', self.m)
        # if self._delete and others != []:
        #     self.m = re.sub(r'[\w\.-]+@@[\w\.-]+', '', self.m)
        # for i in range(len(others)):
        #     others[i] = re.sub(r'@@', '@', others[i])
        # self._email.extend(others)

    def _telephone_filter(self):
        # telephone: xxx-xxxx-xxxx | xxx xxxx xxxx | xxxxxxxxxxx
        pre = r'(13\d|145|147|15\d|18\d|10\d|12\d|17\d|400|800)'
        pattern = pre + r'[-\s]?(\d{4})[-\s]?(\d{4})'
        seg = re.findall(pattern, self.m)
        if seg != []:
            self.m = re.sub(pattern, '', self.m)
        for index in range(len(seg)):
            seg[index] = ''.join(list(seg[index]))
        self._telephone = seg

    def _QQ_filter(self):
        # maybe QQ or something
        QQ = re.findall(r'\d{5,}', self.m)
        for item in QQ:
            if len(item) > 10:
                QQ.remove(item)
        if QQ != []:
            self._QQ = QQ
            for item in QQ:
                self.m = re.sub(item, '', self.m)

    def _wechat_filter(self):
        # maybe wechat
        pattern = re.compile(u'微信|v信|weixin|wx|wechat|vx|威信|维信', re.I)
        mtc = pattern.search(self.m)
        m = self.m
        neighbor = 25
        wechat = []
        while mtc != None:
            # print(mtc.start(), mtc.end())
            wechat.extend(re.findall(r'\w{5,20}',
                        m[max(0, mtc.start() - neighbor) : mtc.start()], re.A))
            wechat.extend(re.findall(r'\w{5,20}',
                        m[mtc.end() : min((mtc.end() + neighbor), len(m))], re.A))
            m = m[min(mtc.end() + neighbor - 5, len(m)) :]
            mtc = pattern.search(m)
        if wechat != []:
            self._wechat = wechat
            for item in wechat:
                self.m = re.sub(item, '', self.m)
        # wechat = re.findall(r'\w{5,}', self.m)
        # for item in wechat:
        #     if len(item) > 20:
        #         wechat.remove(item)
        # if wechat != []:
        #     self.wechat = wechat
        #     for item in wechat:
        #         self.m = re.sub(item, '', self.m)

    def _url_filter(self):
        # only support ASCII
        self._url = re.findall(r'(https?://[!-~]+)', self.m, flags=re.I)
        if self._url != []:
            self.m = re.sub(r'(https?://[!-~]+)', '', self.m, flags=re.I)
        self._url.extend(re.findall(r'(www.[!-~]+)', self.m, flags=re.I))
        self.m = re.sub(r'(www.[!-~]+)', '', self.m, flags=re.I)
        # pattern = r'[!-~]+.[(com|cn|net|xin|ltd|store|vip|cc|game|mom|lol|work|pub|club|xyz|top|ren|bid|loan|red|biz|mobi|me|win|link|wang|date|party|online|site|tech|website|space|live|studio|press|news|video|click|trade|science|wiki|design|pics|photo|help|gift|rocks|org|band|market|software|social|lawyer|engineer|gov.cn|name|info|tv|asia|co|so)][!-~]+'
        # pattern = r'[!-~]+\.[(com|cn|edu|wiki)][!-~]*'
        # self._url = re.findall(pattern, self.m)
        # if self._url != []:
        #     for item in self._url:
        #         self.m = re.sub(item, '', self.m)

    def _emoji_filter(self):
        try:
            # UCS-4
            self._emoji = re.findall(u'[\U00010000-\U0010ffff]', self.m)
            if self._emoji != []:
                self.m = re.sub(u'[\U00010000-\U0010ffff]', '', self.m)
        except re.error:
            # UCS-2
            self._emoji = re.findall(u'[\uD800-\uDBFF][\uDC00-\uDFFF]', self.m)
            if self._emoji != []:
                self.m = re.sub(u'[\uD800-\uDBFF][\uDC00-\uDFFF]', '', self.m)

    def _tex_filter(self):
        # this may look ugly...
        self._tex = re.findall(r'\${1,2}.+?\${1,2}', self.m)
        if self._tex != []:
            for item in self._tex:
                self.m = re.sub(item, '', self.m)
        for i in range(len(self._tex)):
            self._tex[i] = re.sub(r'\$+', '$$', self._tex[i])
