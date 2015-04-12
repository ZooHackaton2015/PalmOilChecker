usage sample:
```shell
python GS1parser.py 8594033271633
```

data received without problems: ({"ERROR":"false"}) stdout->
```json
{"company": "CHOCOLAND\tOvcarecka 305\t280 02 Kolin\tCzech Republic", "ERROR": "false", "provider gln": "8590000000008", "gcp": "859403327", "contact": "Tel:321742411, 774880296\tklara.vazanova@altiskolin.cz", "gln": "8594033270001", "status": "0", "last change": "23.02.2014"}
```

data received with some problem: ({"ERROR":"true"}), status:14 => daily number of requests exceeded? stdout->
```json
{"provider gln": "9501101020016", "contact": "GS1 Global Office GEPIR Support\tTel:+4698148277\tsupport@gepirsupport.org", "ERROR": "true", "company": "GS1 Global Office\tBlue Tower\t1050 Brussels\tNon Members Countries", "gln": "9500000000006", "status": "14", "last change": "", "gcp": ""}
```



exeption raised in the net code: ({"ERROR":"true"}) stdout->
```json
{"exception": "('spam', 'eggs')", "ERROR": "true"}
```
exception contains str(exc)

