===========
cnprep
===========

.. image:: https://travis-ci.org/Momingcoder/cnprep.svg?branch=master
    :target: https://travis-ci.org/Momingcoder/cnprep

Chinese text preprocess
---------------------------

You can extract numbers, email, website, emoji, tex, and delete spaces, punctuations.

Install
-------------

::

    >> pip install cnprep

Usage
--------

::

    from cnprep import Extractor
    ext = Extractor(args=['email', 'number'], limit=5)
    ext.extract(message)

::

    args: option
        e.g. ['email', 'telephone'] or 'email, telephone'
        email
        telephone
        web
        QQ
        tex
        wechat
        message (without punctuation)
        blur (Ⅰ①壹...)
    limit: parameter for get_number (blur)


Also, you can use ''ext.reset_param()'' to reset the parameters.

Attention
-----------
The URL extractor only support ASCII


