# 如何使用

1. 启用模块
```php
"modules" > [
    'search' => ['class' => 'tsy\search\Module'],
    ]
```

２．配置elastcia
```php
"components" => [
        'gameElastica' => [
            'class' => 'tsy\search\components\Elastica',
            'host' => '172.0.0.10',
            'port' => 9201,
        ],
]
```

# 功能

## 指令
1.　统计成品号正出售商品数量，并同步数据到ES
```
yii game/index
```
2. 导出全部游戏为json文件
```
yii game/export-json
```

## 游戏检索
`API`请见文档
1. 自动补全
2. 游戏检索分页
