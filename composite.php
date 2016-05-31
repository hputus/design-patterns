<?php

interface Buyable{
	public function getPrice();
	public function notifyStockRoom();
	public function getDescription();
}

abstract class ShopItem implements Buyable{
	private $price;
	
	public function getPrice(){
		return $this->price;
	}
	
	public function notifyStockRoom(){
		echo "connecting to db, updating stock room to say I've been sold<br/>";
	}
	
	abstract public function getDescription();
}

class Toy extends ShopItem{
	private $price = 10;
	public function getDescription(){
		return "a nice cuddly toy";
	}
}

class ChocolateBar extends ShopItem{
	private $price = 0.5;
	public function getDescription(){
		return "a cheap chocolate bar";
	}
}

class CompositeShopItem implements Buyable{
	private $items = array();

	public function addChild($item){
		$this->items[] = $item;
	}
	
	public function getPrice(){
		$price = 0;
		foreach($this->items as $item){
			$price += $item->getPrice();
		}
	}
	
	public function notifyStockRoom(){
		foreach($this->items as $item){
			$item->notifyStockRoom();
		}
	}
	
	public function getDescription(){
		$description = "";
		foreach($this->items as $item){
			$description .= " and ".$item->getDescription();
		}
		return $description;
	}
}

function buyThing(Buyable $thingToBuy){	
	//get the price of the item
	$price = $thingToBuy->getPrice();
	
	//..some logic to charge it to a bank account perhaps
	
	//..notify the stockroom database that it's been sold
	$thingToBuy->notifyStockRoom();
	
	//..display a messager to the user congratulating them on their purchase.
	echo "Thanks for buying ".$thingToBuy->getDescription()."<br/>";
}

$toy = new Toy();
$bar = new ChocolateBar();

//in separate transactions...
echo "<h3>Doing in separate transactions...</h3>";
buyThing($toy);
buyThing($bar);

echo "<h3>Doing in single transaction using composite...</h3>";
$comp = new CompositeShopItem();
$comp->addChild($toy);
$comp->addChild($bar);
buyThing($comp);