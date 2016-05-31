<?php

//all chainables must have a function that handles an event
interface Chainable{
	public function handle($eventName);
}

//all child classes should have a parent that is passed to the constructor.
//this is so it knows who to notify when an event happens that it cannot handle itself.
abstract class Child{
	protected $parent;
	
	public function __construct(Chainable $parent){
		$this->parent = $parent;
	}
}

//the window is at the top of the hierarchy in this program. It attempts to 
//handle the event passed to it, but if it can't it will throw an exception.
class Window implements Chainable{	
	public function handle($eventName){
		echo get_class($this)." attempting to handle $eventName\n";
		switch($eventName){
			case "click":
				echo "hiding the window\n";
				break;
			default:
				throw new Exception('Cannot handle event '.$eventName);
				break;
		}
	}
}

//the form just passes all events up to the window in this case.
class Form extends Child implements Chainable{
	public function handle($eventName){
		echo get_class($this)." attempting to handle $eventName\n";
		switch($eventName){
			default:
				$this->parent->handle($eventName);
				break;
		}
	}
}

//the button class can handle mouseover events itself, but everything else will
//just be passed up to its parent
class Button extends Child implements Chainable{
	public function handle($eventName){
		echo get_class($this)." attempting to handle $eventName\n";
		switch($eventName){
			case "mouseover":
				$this->changeColour();
				break;
			default:
				$this->parent->handle($eventName);
				break;
		}
	}
		
	public function mouseover(){
		echo "\nthe mouseover event has happened\n";
		$this->handle("mouseover");
	}
	
	public function click(){
		echo "\nthe click event has happened\n";
		$this->handle("click");
	}
	
	public function weirdAction(){
		echo "\nthe weirdaction event has happened\n";
		$this->handle("weirdaction");
	}
	
	public function changeColour(){
		echo "changing colour of button\n";
	}
}

echo "<pre>";

//create the object hierarchy (window->form->button)
$win = new Window();
$form = new Form($win);
$btn = new Button($form);

//see what happens when we perform actions on the button
$btn->mouseover();//handled by button
$btn->click();//handled by window
$btn->weirdAction();//no handlers at all