#!/usr/bin/python
# -*-coding:utf-8-*-

"""
convert Chinese to numbers
"""

import re
# chinese_character_pattern = re.compile(ur"([\u4e00-\u9fa5]+)")
# CCP = chinese_character_pattern

from xpinyin import Pinyin
pinyin = Pinyin()

special_char = {
    # Roman
    'Ⅰ': '1',
    'Ⅱ': '2',
    'Ⅲ': '3',
    'Ⅳ': '4',
    'Ⅴ': '5',
    'Ⅵ': '6',
    'Ⅶ': '7',
    'Ⅷ': '8',
    'Ⅸ': '9',
    'Ⅹ': '10',
    # with circle
    '①': '1',
    '②': '2',
    '③': '3',
    '④': '4',
    '⑤': '5',
    '⑥': '6',
    '⑦': '7',
    '⑧': '8',
    '⑨': '9',
    '⑩': '10',
    # others
    '〇': '0',
}

pinyin2number = {
    'ling': '0',
    'yao': '1',
    'yi': '1',
    'er': '2',
    'san': '3',
    'si': '4',
    'wu': '5',
    'liu': '6',
    'qi': '7',
    'ba': '8',
    'jiu': '9',
    'shi': '10',
}


# def extract_chinese(buf):
#     """
#       extract chinese characters
#     """
#     segment_list = []
#     m = CCP.search(buf)
#     while m is not None:
#         segment = m.group(1)
#         segment_list.append(segment)
#         idx = m.start() + len(segment)
#         buf = buf[idx:]
#         m = CCP.search(buf)

#     return segment_list

def get_number(message, limit=4):
    """
    convert Chinese to pinyin and extract useful numbers

    attention:
        1. only for integer
        2. before apply this method, the message should be preprocessed

    input:
        message: the message you want to extract numbers from.
        limit: limit the length of number sequence
    """
    words = pinyin.get_pinyin(message).split('-')
    numbers = []
    tmp = ''
    count = 0
    for w in words:
        if re.search(r'\W', w, re.A):
            for s in list(w):
                if s in special_char.keys():
                    count += 1
                    tmp += special_char[s]
                else:
                    if count >= limit:
                        numbers.append(tmp)
                    count = 0
                    tmp = ''
        elif w in pinyin2number.keys():
            count += 1
            tmp += pinyin2number[w]
        else:
            if count >= limit:
                numbers.append(tmp)
            count = 0
            tmp = ''
    if count >= limit:
        numbers.append(tmp)
    return numbers
