## Introduction

The REST API example implemented by Symfony

## Installation

```
git clone https://github.com/kavw/symfony-sysio-api.git sysio-api
chmod u+x sysio-api/bin/run
sysio-api/bin/run
```


## Checking this API

```
curl -X POST -H "Content-Type: application/json" -d '{"product": 1001, "taxNumber": "GR123456789", "couponCode": "DP6"}' http://localhost:22080/calculate-price
```
```
curl -X POST -H "Content-Type: application/json" -d '{"product": 1001, "taxNumber": "GR123456789", "couponCode": "DP6", "paymentProcessor": "paypal"}' http://localhost:22080/purchase
```
