<?php

class Block{
	private $imgData;
	
	public function __construct(){
		$this->imgData = file_get_contents('block.jpg');
	}
	
	public function draw($x, $y){
		return $this->imgData;
	}
}

class BlockFactory{
	private $cached;
	
	public function makeBlock(){
		if(is_null($this->cached)){
			$this->cached = new Block();
		}
		return $this->cached;
	}
}

/*for($i = 1; $i <= 10000; $i++){
	$block = new Block();
	$block->draw($i,$i);
}*/

$factory = new BlockFactory();
for($i = 1; $i <= 10000; $i++){
	$block = $factory->makeBlock();
	$block->draw($i,$i);
}

echo memory_get_peak_usage();