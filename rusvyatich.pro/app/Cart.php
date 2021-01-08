<?php

namespace App;

/*
*
*Класс содержащий данные корзины
*
*/
class Cart 
{
    
	public $items = null;
	public $itemsSerial = null;
	public $totalPrice = 0;

	public function __construct($oldCart)
	{
		if ($oldCart) {
			$this->itemsSerial = $oldCart->itemsSerial;
			$this->items = $oldCart->items;
			$this->totalPrice = $oldCart->totalPrice;
		}
	}
	
	/*Возвращает bool успеха добавления в корзину индивидуального ножа*/
	public function add($item, $id)
	{
	
		if (!($this->items && in_array($id, $this->items))) {
			$this->items[] = $id;
			$this->totalPrice += $item->price;
			return true;
		}else {
			return false;
		}

	}

	/*Возвращает bool успеха добавления в корзину серийного ножа*/
	public function addSerial($item, $id, $count)
	{
		$flag = false;
		$i = 0;
		$count = intval($count);
		$storedItem = ['count'=>$count, 'id'=>$id];
		if($this->itemsSerial) {
			foreach($this->itemsSerial as $key => $value)
			{
			    if ($id == $value['id'])
			    {
			        $flag =true;
			        $i = $key;
			        break;
			    }
			}
		}
		if ($flag) {
			$this->itemsSerial[$i]['count'] += $count;
			$this->totalPrice += $item->price*$count;
		} else {
			$this->itemsSerial[] = $storedItem;
			$this->totalPrice += $item->price*$count;
		}
		
		return true;
	}
	
	/*Возвращает bool успеха удаления из корзины индивидуального ножа*/
	public function remove($item, $id)
	{
		$flag = false;
		if ($this->items && in_array($id, $this->items)) {

        	for ($i = 0; $i < count($this->items) ; $i++) { 
        		if ($this->items[$i] === $id && $flag === false) {
        			unset($this->items[$i]);
        			$this->items = array_values($this->items);
        			$this->totalPrice -= $item->price;
        			$flag = true;
        		}
        	}

        	return true;
		}else {
			return false;
		}

	}

	/*Возвращает bool успеха удаления из корзины серийного ножа*/
	public function removeSerial($item, $id)
	{
		$flag = false;
		$flagIsset = false;
		if ($this->itemsSerial) {
			foreach($this->itemsSerial as $key => $value)
			{
			    if ($id == $value['id'])
			    {
			        $flagIsset = true;
			        $j = $key;
			        break;
			    }
			}
			if (!$flagIsset) return false;
        	for ($i = 0; $i < count($this->itemsSerial) ; $i++) { 
        		if ($this->itemsSerial[$i]['id'] === $id && $flag === false) {
        			$this->totalPrice -= $item->price * $this->itemsSerial[$i]['count'];
        			unset($this->itemsSerial[$i]);
        			$this->itemsSerial = array_values($this->itemsSerial);
        			$flag = true;
        		}
        	}

        	return true;
		}else {
			return false;
		}

	}

}
