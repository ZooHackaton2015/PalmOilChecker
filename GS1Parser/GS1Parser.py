import json
from lxml import etree;
import urllib
import urllib.parse
import urllib.request
from re import sub
import sys

def parse(ean:str) -> str:
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

    reqdata = urllib.parse.urlencode(data,True,encoding='utf-8').encode('utf-8');
    req = urllib.request.Request("http://gepir.gs1.org/v32/xx/gtin.aspx?Lang=en-US",reqdata);
    resp = urllib.request.urlopen(req);
    respData = resp.read();

    tree = etree.fromstring(respData,etree.HTMLParser());
    table = tree.xpath("//table[@id='resultTable']")[0];
    headers = [e.strip().lower() for e in table.xpath(".//th/text()")];
    
    values = ["\t".join([sub(r"(\s)+",' ',x).strip() for x in e.itertext()]).strip() for e in table.xpath(".//td")];

    if(len(values)!=len(headers)):
        print("{ERROR:true}");

    dict = {};

    if(values[0] == "9500000000006"):#default company
        dict["ERROR"] = "true";


    for i in range(len(values)):
        dict[headers[i]] = values[i];

    if(not "ERROR" in dict):
        dict["ERROR"] = "false";

    print(json.dumps(dict));





if __name__=='__main__':
    parse(sys.argv[1])