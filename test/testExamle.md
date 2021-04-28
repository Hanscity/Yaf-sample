# 测试用例

## Market(市场信息)

### 市场信息--新增
``` 

curl -X POST -H "Content-Type: application/json" -d '{"agri_product":1,"buy_cell_type":2,"brand":"huanghuazhan","unit_price":"2900","num":200,"trade_area":"wuxue","specification":"shuifen:14"}' http://yaf.test/index/market/add
```


### 市场信息--列表

```
curl -X POST -H "Content-Type: application/json" -d '{}' http://yaf.test/index/market/list

```


