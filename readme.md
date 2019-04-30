# index
- status
  - ![travis-ci-master](https://travis-ci.org/cscolabear/rainy-to-do-app-api.svg?branch=master)
- [PHP Requirement](#requirement)
- [GraphQL Request Example](#graphql-request-example)
  - [fetch data](#fetch-data)
  - [batch insert](#batch-insert)

---

### Requirement

配合 https://github.com/pamcy/Rainy-To-Do-App 建立的 API

(API 介面使用 graphql - http://graphql.org/)

使用兩個不一樣的 Graphql 套件
- 目前 master 套件為 https://github.com/rebing/graphql-laravel

- branch: https://github.com/cscolabear/rainy-to-do-app-api/tree/nuwave/lighthouse
  - https://github.com/nuwave/lighthouse

- client 端個人選用 https://github.com/f/graphql.js/
  - 用什麼方式都可以，`建議用 post 把 graphql request string 送出`
  - 目前 get, post 都是啟用狀態; config/graphql.php - schemas.method = ['get', 'post']

- dev playground
  - 使用套件 `mll-lab/laravel-graphql-playground`
  - 可以使用 `https://[domain]/graphql-playground` 進行測試
  - ![graphql-playground](https://user-images.githubusercontent.com/4863629/56945400-a79f3100-6b59-11e9-98d2-b841ed2668fe.png)

---


### graphql request example

 - #### fetch data
```graphql
query {
  products(
    count: 2
    # page: 2
    orderBy: {
      field: "price"
      order: DESC
    }
  )
  {
    data {
      id
      title
      price
      source {
        name
      }
      category {
        name
      }
    }
    total
    per_page
    last_page
  }
}
```

or http get request
```http
/graphql?query=query {products(count:2 orderBy:{field:"price" order:DESC}){data{id title price source{name} category{name}}total per_page last_page}}
```

response
```json
{
  "data": {
    "products": {
      "data": [
        {
          "id": 2,
          "source_id": 1,
          "title": "親子生態觀察 - 基隆八斗子潮境公園",
          "description": "做為海島子民，我們應該從小多了解台灣美麗的海岸線地型，才會知道豐富生態有多麼的珍貴。趁著兒童連假，帶著孩子來到基隆八斗子潮境公園，探訪這裡都住了哪些可愛的居民？面臨艱困的生存環境，他們又有哪些厲害的本領呢？",
          "price": 600,
          "link": "/product/3196",
          "img": "//d1f5je0f1yvok5.cloudfront.net/photo/n/g/f/ngfJsvXYDNfBjmByMVLULQ_o.jpg",
          "created_at": "2019-04-30 03:14:15",
          "source": {
            "name": "niceday"
          },
          "category": {
            "name": "愛上戶外"
          }
        },
        {
          "id": 4,
          "source_id": 1,
          "title": ": 多肉木器小品 : 禪風與森林綠意的結合",
          "description": "與其他植物相較，厚實的多肉植物多了種樸拙穩重，透過溫潤的木器及生氣蓬勃的多肉植物，組合出溫暖人心的多肉小品。適合擺在窗邊，跟著多肉追逐陽光、感受微風，為生活點綴森林的氣息，送禮自用兩相宜！",
          "price": 500,
          "link": "/product/3729",
          "img": "//d1f5je0f1yvok5.cloudfront.net/photo/G/d/K/GdKxV_6,AERcQs1883_HTQ_o.jpg",
          "created_at": "2019-04-30 03:14:15",
          "source": {
            "name": "niceday"
          },
          "category": {
            "name": "藝文手作"
          }
        }
      ],
      "total": 5,
      "per_page": 3,
      "last_page": 2
    }
  }
}
```


 - #### batch insert
```graphql
mutation {
  insertProducts(
    data: [
      {
      	source: "niceday"
        prefix_url: "//play.niceday.tw"
      	category: "愛上戶外"
      	title: "title 123"
      	description: "desc 123"
      	link: "cola.io/1234"
      	img: "//cola.io/abc.jpg"
      	price: "$ 1,234 起"
      }
    ]
  ) {
    affected_rows
  }
}
```

or http get request
```http
/graphql?query=mutation{insertProducts(data:[{source:"niceday" prefix_url:"//play.niceday.tw" category:"愛上戶外" title:"title 123" description:"desc 123" link:"cola.io/1234" img: "//cola.io/abc.jpg" price:"$ 1,234 起"}]){affected_rows}}
```

response
```json
{
  "data": {
    "insertProducts": {
      "affected_rows": 1
    }
  }
}
```
