#!/usr/bin/python
# -*- coding: utf-8 -*-
# encoding: utf-8
# coding=utf-8
import urllib2, urllib, json, cookielib
import os, sys, re
 
cookies = cookielib.CookieJar()
cookies.clear()
opener = urllib2.build_opener( urllib2.HTTPCookieProcessor(cookies) )
opener.addheaders = [('User-Agent','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:37.0) Gecko/20100101 Firefox/37.0'),
                     ('Referer', 'http://www.codecheck.info')]

def search(code, v=0):
    
    out = {}
    timeout = 5     

    res = opener.open('http://www.codecheck.info/product.search?q='+code+'&OK=Suchen', timeout=timeout)
    
    q = re.search( 'productId:\s+(\d+),', res.read(), re.DOTALL|re.MULTILINE)
    if q :
        productId = q.group(1)
    else:
        out['error'] = True
        return out
    
    if v : print 'productId', productId
    
    ajaxKey = None
    for c in cookies:
        if c.name == 'CC_AJAX' :
            ajaxKey = c.value
            
    if v : print 'ajaxKey', ajaxKey
    
    try:
        res = opener.open('http://www.codecheck.info/produkt/inhaltsstoffe', urllib.urlencode({'ajaxKey':ajaxKey, 'productId':productId}), timeout=timeout )
        out['composition'] = re.sub( '<.*?>', '', res.read())
    except:
        pass
    
    try:
        res = opener.open('http://www.codecheck.info/produkt/hersteller', urllib.urlencode({'ajaxKey':ajaxKey, 'productId':productId}), timeout=timeout )
        out['company'] = res.read()
    except:
        pass

    return out

if __name__ == '__main__':
    print json.dumps( search(sys.argv[1]) ) 


