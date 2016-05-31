<?php
//memento - useful for undoing stuff. You can save and restore the state of things.

//define an interface that all stateful things must adhere to. Here it must have two methods:
//savestate which will be responsible for saving the state of itself, and restoreState which
//takes a memento as an argument. This is responsible for restoring the state to match that of
//the memento.
interface Stateful{
	public function saveState();
	public function restoreState(Memento $memento);
}

//an interface for all Memento objects - they must have a getState method.
interface Memento{
	public function getState();
}

//a memento especially for use with the rectangle class. It takes a width and height
//and saves these as private properties. It's getState method then returns these in an object.
class RectangleMemento implements Memento{
	private $height, $width;
	
	public function __construct($height, $width){
		$this->height = $height;
		$this->width = $width;
	}
	
	public function getState(){
		return (object) array(
			"height" => $this->height, 
			"width" => $this->width
		);
	}
}

//a rectangle that can save its state. It has setHeight and setWidth methods. When we want
//to save the state of the object, it creates a new RectangleMemento object that contains the
//Rectangle's current height and width. When we want to restore it, we take a memento object and
//restore its state.
class Rectangle implements Stateful{
	private $height, $width;
	
	public function setHeight($height){
		$this->height = $height;
	}
	public function setWidth($width){
		$this->width = $width;
	}
	public function saveState(){
		return new RectangleMemento($this->height,$this->width);
	}
	public function restoreState(Memento $memento){
		$oldState = $memento->getState();
		$this->height = $oldState->height;
		$this->width = $oldState->width;
	}
}

//create a new rectangle and resize it
$rect = new Rectangle();
$rect->setHeight(10);
$rect->setWidth(10);

//save state
$memento = $rect->saveState();
var_dump($memento);

//make some changes
$rect->setHeight(100);
var_dump($rect->saveState());

//actually, let's undo that action
$rect->restoreState($memento);
var_dump($rect->saveState());