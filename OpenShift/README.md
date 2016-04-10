# Pal Oil Checker End User App on OpenShift

Administration of the application on OpenShift:
* https://openshift.redhat.com/app/console/domain/zoohackaton

Try it:
* http://palmoil-zoohackaton.rhcloud.com/barcodes/3045140105502
   * 200 OK - doesn't contain oil
* http://palmoil-zoohackaton.rhcloud.com/barcodes/5201360527205
   * 200 OK - contains oil
* http://palmoil-zoohackaton.rhcloud.com/barcodes/0000000000000
   * 404 Not Found ... barcode we don't know

Administration:
* OpenShift admin:
   * https://openshift.redhat.com/app/console/applications
* Node.js to Mongo connection status:
   * http://palmoil-zoohackaton.rhcloud.com/db-connection-status
* HA proxy:
   * http://palmoiladmin-zoohackaton.rhcloud.com/haproxy-status/
* PING to load data from Mongo to Node.js cache - to be called by admin app:
   * http://palmoil-zoohackaton.rhcloud.com/watermark



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

---