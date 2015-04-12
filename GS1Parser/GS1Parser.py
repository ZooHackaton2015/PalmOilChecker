import json
from lxml import etree;
import urllib
import urllib2
from re import sub
import sys

def parse(ean):
    """parser for http://gepir.gs1.org/v32/xx/gtin.aspx?Lang=en-US"""
    data = {"ctl0_cphMain_LoginPanel_ScriptManager_HiddenField":	";;AjaxControlToolkit:en-US:c5c982cc-4942-4683-9b48-c2c58277700f:865923e8:411fea1c:e7c87f07;AjaxControlToolkit, Version=1.0.20229.20821, Culture=neutral, PublicKeyToken=28f01b0e84b6d53e:en-US:c5c982cc-4942-4683-9b48-c2c58277700f:865923e8:91bd373d:ad1f21ce:596d588c:8e72a662:411fea1c:acd642d2:77c58d20:14b56adc:269a19ae:d7349d0c",
            "__EVENTTARGET":	"",
            "__EVENTARGUMENT":	"",
            "_ctl0_cphMain_TabContainerGTIN_ClientState":	"{\"ActiveTabIndex\":0,\"TabState\":[true]}",
            "__VIEWSTATE":	"",
            "__VIEWSTATEGENERATOR":"",
            "_ctl0:cphMain:LoginPanel:LoginCtrl:UserName":"",
            "_ctl0:cphMain:LoginPanel:LoginCtrl:Password":"",
            "_ctl0:cphMain:TabContainerGTIN:TabPanelGTIN:txtRequestGTIN":	ean,
            "_ctl0:cphMain:TabContainerGTIN:TabPanelGTIN:rblGTIN":	"party",
            "_ctl0:cphMain:TabContainerGTIN:TabPanelGTIN:btnSubmitGTIN":	"Search"};

    reqdata = urllib.urlencode(data,True).encode('utf-8');
    req = urllib2.Request("http://gepir.gs1.org/v32/xx/gtin.aspx?Lang=en-US",reqdata);
    resp = urllib2.urlopen(req, timeout = 10);
    respData = resp.read();

    tree = etree.fromstring(respData,etree.HTMLParser());
    tables = tree.xpath("//table[@id='resultTable']");

    if len(tables) <= 0:
        raise Exception("response does not contain required data (invalid EAN number)");

    table  = tables[0];
    headers = [e.strip().lower() for e in table.xpath(".//th/text()")];
    values = ["\t".join([sub(r"(\s)+",' ',x).strip() for x in e.itertext()]).strip() for e in table.xpath(".//td")];

    if(len(values)!=len(headers)):
        print("{error:true}");

    dict = {};
    dict["error"] = "false";
    if(values[0] == "9500000000006"):#default company
        dict["error"] = "true";


    for i in range(len(values)):
        dict[headers[i]] = values[i];

    print(json.dumps(dict));


if __name__=='__main__':
    try:
        parse(sys.argv[1])
    except Exception as e:
        dict = {"error":"true"}
        dict["exception"] = str(e);
        print(json.dumps(dict));