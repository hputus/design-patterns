<?php
//Decorator pattern - a nice way of adding subtle new functionality to an object at run-time.
//not adding new public methods, but enhancing what the public methods already do.
//Especially useful if there are a great many possible permutations which would involve a lot of
//subclasses being created.
//Also good because it separates this different functionality into different classes: SRP. The 
//SimpleWindow class doesn't control scrollbars or borders; this functionality is all contained
//within separate decorator classes.

//an interface that windows will implement
interface Window{
	public function draw();
}

//a simple window with nothing special about it...
class SimpleWindow implements Window{
	public function draw(){
		echo "drawing a simple window";
	}
}

//a blueprint for a window decorator class - stores a window as a property
//and overrides its draw method
abstract class WindowDecorator implements Window{
	private $window;
	
	public function __construct(Window $window){
		$this->window = $window;
	}
	
	public function draw(){
		$this->window->draw();	
	}
}

//scrollable window decorator - adds scrollbars when drawing the window
class ScrollableWindow extends WindowDecorator{
	public function draw(){
		parent::draw();
		$this->drawScrollbar();
	}
	
	private function drawScrollbar(){
		echo '...with a scrollbar';
	}
}

//border window decorator - adds border when drawing the window
class BorderWindow extends WindowDecorator{
	public function draw(){
		parent::draw();
		$this->drawBorder();
	}
	
	private function drawBorder(){
		echo '...with a nice border';
	}
}

//coloured window decorator - adds strange colours when drawing the window
class WeirdColouredWindow extends WindowDecorator{
	public function draw(){
		parent::draw();
		$this->changeButtonColours();
	}
	
	private function changeButtonColours(){
		echo '...with weird coloured buttons';
	}
}

//Now we can have any number of combinations of these three
//
echo "<pre>";
$win1 = new SimpleWindow();
$win1->draw();

echo "\n";

$win2 = new ScrollableWindow(new SimpleWindow());
$win2->draw();

echo "\n";

$win3 = new WeirdColouredWindow(new ScrollableWindow(new SimpleWindow()));
$win3->draw();

echo "\n";

$win4 = new BorderWindow(new ScrollableWindow(new SimpleWindow()));
$win4->draw();