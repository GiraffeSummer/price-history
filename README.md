# Price History



### **Routes**

#### `/`  
just returns 'done'  

#### `/test`  
Runs [GeneratePriceHistory](#generatepricehistory)

#### `/history`  
see the history of all the products as json

#### `/history/{product:id}/raw`  
see the history of a specific product as json

#### `/history/{product:id}`  
see the price history graph of a specific product

### **Commands**

#### GeneratePriceHistory
- `php artisan generate-price-history`  
Scan for changes in price history

#### GenerateRandomHistory
- `php artisan generaterandomhistory {product} {entries=10}`  
Randomly add price history to selected product

#### GenerateRandomPrices
- `php artisan app:generate-random-price`  
Randomly change prices on all products