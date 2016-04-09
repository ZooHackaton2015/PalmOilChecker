# Pal Oil Checker Application



# End User REST

Try it:
* http://palmoil-zoohackaton.rhcloud.com/barcodes/3045140105502
   * 200 OK - doesn't contain oil
* http://palmoil-zoohackaton.rhcloud.com/barcodes/5201360527205
   * 200 OK - contains oil
* http://palmoil-zoohackaton.rhcloud.com/barcodes/1234567890123
   * 404 Not Found ... barcode we don't know



# Admin Application

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
