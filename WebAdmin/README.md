# Pal Oil Checker Admin Application

# Prerequisites
OpenShift environment variables:
```
OPENSHIFT_MONGODB_DB_GEAR_DNS=570a10472d52717c48000004-zoohackaton.rhcloud.com
OPENSHIFT_MONGODB_DB_GEAR_UUID=570a10472d52717c48000004
OPENSHIFT_MONGODB_DB_HOST=570a10472d52717c48000004-zoohackaton.rhcloud.com
OPENSHIFT_MONGODB_DB_PASSWORD=QzfJY8-mhELh
OPENSHIFT_MONGODB_DB_PORT=54861
OPENSHIFT_MONGODB_DB_URL=mongodb://admin:QzfJY8-mhELh@570a10472d52717c48000004-zoohackaton.rhcloud.com:54861/
OPENSHIFT_MONGODB_DB_USERNAME=admin
OPENSHIFT_MONGODB_LD_LIBRARY_PATH_ELEMENT=/opt/rh/v8314/root/usr/lib64:/opt/rh/mongodb24/root/usr/lib64
```

## Data Model

Resources:
* product
    * Long barcode          ... 32b system 10 digits > 64b system 13 digits needed
    * Bool safe
    * Integer approverId    ... user who approved
    * Long timestamp        ... modification timestamp
```    
    { "barcode": 1234567890123, "safe" : true, "approver-id" : 1, "timestamp" : 2147483647 }
```    
* user
    * Integer id
    * String email
    * String password
    * DateTime registered
    * Enum role
```    
    { }
```    

## Workflow

End user service use cases:
* getProduct(barcode) -> OK | NOK | UNKNOWN
    * 404
    * 200 { "contains-oil": true }
    * 200 { "contains-oil": false }

Admin service HTTPS use cases:
* login()
* logout()

* **NON USE CASE** getProducts()
* addProduct(barcode, safe)
* updateProduct(barcode, safe)
* deleteProduct(barcode)

* getUsers()
* addUser(email, password, role) -> id;
* updateUser(email, password, role)
* getUser(id)
* removeUser(username)

Admin service REST:
* login()
* addProduct(barcode, safe)
    * basic authentication
    * barcodes/
    * POST { "contains-oil": false; "barcode": 1234567890123 }
