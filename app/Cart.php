<?php

namespace App;


class Cart
{
    public $products = null;
    public $totalQuantity = 0;
    public $totalPrice = 0;
    public function __construct($prevCart){
        if ($prevCart){
            $this->products = $prevCart->products;
            $this->totalQuantity = $prevCart->totalQuantity;
            $this->totalPrice = $prevCart->totalPrice;
        }
    }
    public function addProduct($product,$id,$deliveryPrice){
        if($this->totalPrice == 0){
            $this->totalPrice += $deliveryPrice;
        }

        $storedProduct = ['quantity' => 0, 'price'=>$product->price, 'product'=>$product ];
        if ($this->products){
            if(array_key_exists($id, $this->products)){
                $storedProduct = $this->products[$id];
            }
        }
        $storedProduct['quantity']++;
        $storedProduct['price'] = $product->price * $storedProduct['quantity'];
        $this->products[$id] = $storedProduct;
        $this->totalQuantity++;
        $this->totalPrice += $product->price;
    }

    public function removeProduct($id){
        $this->products[$id]['quantity'] -= 1;
        $this->products[$id]['price'] -= $this->products[$id]['product']['price'];
        $this->totalQuantity -= 1;
        $this->totalPrice -= $this->products[$id]['product']['price'];

        if ($this->products[$id]['quantity'] <= 0) {
            unset($this->products[$id]);
        }
    }
}
