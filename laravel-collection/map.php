<?php
/**
 * @link http://www.kyrieup.com/
 * @package map.php
 * @author kyrie
 * @date: 2017/4/4 下午4:21
 */

require __DIR__ . '/vendor/autoload.php';
$orders = [[
    'id'            =>      1,
    'user_id'       =>      1,
    'number'        =>      '13908080808',
    'status'        =>      0,
    'fee'           =>      10,
    'discount'      =>      44,
    'order_products'=> [
        ['order_id'=>1,'product_id'=>1,'param'=>'6寸','price'=>555.00,'product'=>['id'=>1,'name'=>'蛋糕名称','images'=>[]]],
        ['order_id'=>1,'product_id'=>1,'param'=>'7寸','price'=>333.00,'product'=>['id'=>1,'name'=>'蛋糕名称','images'=>[]]],
    ],
]];

$orderProducts = collect($orders)->map(function ($orders){
    return $orders['order_products'];
});
// get price
// longest way
$price = collect($orders)->map(function ($order){
    return $order['order_products'];
})->flatten(1)->map(function ($inside){
    return $inside['price'];
});

// shorter way
$priceOfProducts = $orderProducts->flatten(1)->map(function($product){
    return $product['price'];
});

// shortest way
$priceToo = collect($orders)->flatMap(function ($order){
    return $order['order_products'];
})->pluck('price');

// get the total price
$total = $priceToo = collect($orders)->flatMap(function ($order){
    return $order['order_products'];
})->pluck('price')->sum();

// or like this
$totalToo = $priceToo = collect($orders)->flatMap(function ($order){
    return $order['order_products'];
})->sum('price');

// but the better ways are as follows
dump(collect($orders)->pluck('order_products')->flatten(1)->sum('price'));
dump(collect($orders)->pluck('order_products.*.price')->flatten(1)->sum());

dd($orderProducts,$priceOfProducts,$price, $priceToo, $totalToo);
