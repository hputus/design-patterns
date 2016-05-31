<?php
//We are writing a program that outputs to PDF. We have a Document interface as follows:
interface Document{
	public function addPage();
	public function removePage();
	public function addText($text);
}

//and our PDF generator class:
class PdfDocumentCreator implements Document{
	public function addPage(){
		echo "Adding a page to the pdf document<br/>";
	}
	public function removePage(){
		echo "Removing a page to the pdf document<br/>";
	}
	public function addText($text){
		echo "Adding text ($text) to the pdf document<br/>";
	}
}

//Somewhere in our program we have a function that is responsible for constructing a document. It takes
//in an instance of a class that implements the Document interface, adds a page and adds some text. 
//It then (for some reason) removes the page. In reality it's probably going to be a lot more complex 
//than this, adding multiple pages, maybe some images, and perhaps applying some formatting. 
//But let's stick with this for now.
function createDocument(Document $doc){
	$doc->addPage();
	$doc->addText("some text");
	$doc->removePage();
}

//Here's how we create our PDF document:
createDocument(new PdfDocumentCreator());

echo "<hr/>";

//However, now we want to allow our program to create Word documents too. We found an external library
//that does this. It contains a class called WordDocumentCreator that does everything we
//need it to. The only issue is that the methods are slightly different...we can't just change
//WordDocumentCreator to match our current interface because other classes in this library may
//rely on it, plus if it's a complex class it's going to be a lot of hard work, and you will very
//likely introduce bugs in the process, break unit tests, etc. Here is the class:
class WordDocumentCreator{
	public function constructPage($index){
		echo "adding a page to a word document at index $index<br/>";
	}
	public function deletePage($index){
		echo "deleting page from word document at index $index<br/>";
	}
	public function addParagraph(){
		echo "adding a paragraph to the word document<br/>";
		return 1;
	}
	public function addTextToParagraph($text, $paragraphNumber){
		echo "adding text ($text) to paragraph $paragraphNumber of the word document<br/>";
	}
}

//To make it compatible with our current code we can create an adapter that implements the Document 
//interface. We can pass the adapter into our existing function now. It takes a WordDocumentCreator 
//as an argument in its constructor and calls the various methods internally.
class WordDocumentAdapter implements Document{
	protected $adaptee;
	
	public function __construct(WordDocumentCreator $adaptee){
		$this->adaptee = $adaptee;
	}
	
	public function addPage(){
		$this->adaptee->constructPage(1);
	}
	
	public function removePage(){
		$this->adaptee->deletePage(1);
	}
	
	public function addText($text){
		$this->adaptee->addParagraph();
		$this->adaptee->addTextToParagraph("this is some text",1);
	}
}

//Now we can use this to pass an instance of WordDocumentCreator into our function
$adapter = new WordDocumentAdapter(new WordDocumentCreator());
createDocument($adapter);

//And there we have it! We have managed to extend our program's functionality without having to modify
//our existing code or the new WordDocumentCreator class! Hooray for the adapter pattern!