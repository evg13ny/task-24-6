<?php

// создал магазин

class Store
{
	public static function get()
	{
		static $myStore = null;
		if ($myStore == null)
		{
			$myStore = new Store();
		}
		return $myStore;
	}

	private function __construct()
	{
		$this->initialize();
		$this->connectionWithDB();
	}

	private function initialize();
	private function connectionWithDB();
}

// в котором есть товары

interface IItem
{
	public function setItemCount($itemCount);
	public function getItemCount();
	public function setItemId($itemId);
	public function getItemId();
	public function setPrice($itemPrice);
	public function getPrice();
}

// различные скидки

interface IDiscount
{
	public function applyPercentageDiscount($percentageDiscount); // процентная скидка
	public function applyShippingDiscount($shippingDiscount); // скидка на доставку
	public function applyStockItemPrice($stockPrice); // товар по акции
}

class Goods implements IItem, IDiscount
{
	protected $itemCount = 0;
	protected $itemId;
	protected $itemPrice;

	protected $percentageDiscount;
	protected $shippingDiscount;
	protected $stockPrice;

	public function setItemCount($itemCount);
	public function getItemCount();
	public function setItemId($itemId);
	public function getItemId();
	public function setPrice($itemPrice);
	public function getPrice();

	public function applyPercentageDiscount($percentageDiscount);
	public function applyShippingDiscount($shippingDiscount);
	public function applyStockItemPrice($stockPrice);

	public function getPriceWithDiscounts()
	{
		$priceWithDiscount = applyPercentageDiscount($this->itemPrice);
		$priceWithDiscount = applyShippingDiscount($priceWithDiscount);
		return $priceWithDiscount;
	}
}

// эти товары кладутся в корзину

class Basket
{
	private $userId;
	private $itemsArray = [];

	private function __construct($userId) // к корзине и заказу привязывается пользователь
	{
		$this->userId = $userId;
	}

	public function addGoods($Goods)
	{
		if($Goods->getCount > 0) // в корзину и заказ нельзя добавить товар, у которого количество ниже 0
		{
			array_push($this->itemsArray, $Goods);
		}
	}

	public function checkOut() // всё, что лежит в корзине, становится заказом
	{
		$newOrder = new Order($this->itemsArray, $this->userId);
		return $newOrder;
	}
}

// также есть пользователи

class UserData
{
	private $userAddress;
	private $userPhone;
	private $userEmail;
	private $userPersonalDiscount;

	public function setUserAddress($userAddress);
	public function getUserAddress();
	public function setUserPhone($userPhone);
	public function getUserPhone();
	public function setUserEmail($userEmail);
	public function getUserEmail();
	public function setUserPersonalDiscount($userPersonalDiscount);
	public function getUserPersonalDiscount();
}

class User
{
	private $userId;
	protected $userData;

	private function __construct()
	{
		$this->userData = new UserData();
	}
}

// формируется заказ

class Order
{
	protected $userId;
	private $itemsArray = [];

	private function __construct($itemsArray, $userId)
	{
		$this->userId = $userId;
		$this->itemsArray = $itemsArray;
	}

	public function calculateTotalSum()
	{
		$sum = 0;
		for ($i = 0; $i < count($this->itemsArray); $i++)
		{
			$sum += $this->itemsArray[i].getPriceWithDiscounts;
		}
		return $sum;
	}

	public function getItems();
	public function getItemsCount();
	public function addItem($item);
	public function deleteItem($item);
}

class OrderRepository // хранилище
{
	private $source;

	public function setSource(IOrderSource $source)
	{
		$this->source = $source;
	}

	public function load($orderId)
	{
		return $this->source->load($orderId);
	}

	public function save($order);
	public function update($order);
}

interface IOrderSource
{
	public function load($orderId);
	public function save($newOrder);
	public function update($newOrder);
	public function delete($newOrder);
}

class MySQLOrderSource implements IOrderSource
{
	public function load($orderId);
	public function save($newOrder);
	public function update($newOrder);
	public function delete($newOrder);
}

class ApiOrderSource implements IOrderSource
{
	public function load($orderId);
	public function save($newOrder);
	public function update($newOrder);
	public function delete(newOrder);
}

class OrderViewer // отображение заказа
{
	public function printOrder($newOrder);
	public function showOrder($newOrder);
}

// служба доставки

class Delivery
{
	public function sendOrder($newOrder);
}

$user = new User();
$basket = new Basket($user);
$basket->addGoods();
$basket->checkout();
$basket->sendOrder();