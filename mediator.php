<?php
/**
 * An example of how the mediator pattern can be used to make an action on one class
 * affect another. In this example we have a searchbox that is typed into. We may want
 * that to affect many other objects in the program (e.g. highlighting text in the window, or searching the internet), 
 * and our searchbox object probably
 * shouldn't have to know about all these other objects. Therefore we can use a mediator
 * class to link them together.
 */

//an abstract class that all observable classes can inherit from
abstract class Observable{
	private $observers = array();
	
	function register($observer){
		$this->observers[] = $observer;
	}
	
	protected function notify($hint){
		foreach($this->observers as $observer){
			$observer->update($hint);
		}
	}
}

//a search box that people can type into
class SearchTextBox extends Observable{
	private $searchTerm = '';
	
	//every time a user types a letter, add it to the search term and notify all
	//listeners that the search term has been updated.
	public function type($word){
		$this->searchTerm .= $word;
		$this->notify($this->searchTerm);
	}
}

interface Searchable{
	public function search($word);
}

//a document that can highlight some text
class Document implements Searchable{
	private $content = 'some kind of content in the document - it was probably loaded from a file<br/>';
	
	public function search($word){
		$this->highlight($word);
	}
	
	private function highlight($word){
		echo str_replace($word,'<b>'.$word.'</b>',$this->content);
	}
}

class Internet implements Searchable{
	private $content = 'some kind of content from the internet!<br/>';
	
	public function search($word){
		$this->highlight($word);
	}
	
	private function highlight($word){
		echo str_replace($word,'<b>'.$word.'</b>',$this->content);
	}
}

class Mediator{
	private $observedClass;
	private $affectedClass;
	
	function __construct(Observable $observedClass, Searchable $affectedClass){
		$this->observedClass = $observedClass;
		$this->affectedClass = $affectedClass;
		$observedClass->register($this);
	}
	
	function update($hint){
		$this->affectedClass->search($hint);
	}
}

$textbox = new SearchTextBox();
$doc = new Document();
$mediator = new Mediator($textbox, $doc);
$internet = new Internet();
$mediator = new Mediator($textbox, $internet);
$textbox->type('content');

//you could perhaps modify this to add an array of affected objects to the mediator class, instead of creating
//several separate mediators...