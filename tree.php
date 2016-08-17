<?php
	class tree{
		public $treeRoot = array(
			'name'=>'',
			'count'=>0,
			'child'=>array()
		);
		
		public function insertNode($word){
			$charArray = str_split($word);
			
			$currentNode = & $this->treeRoot;
			
			for($i=0, $len=count($charArray); $i<$len; $i++){
				$currentChar = $charArray[$i];
				
				if(!isset($currentNode['child'][$currentChar])){
					$currentNode['child'][$currentChar] = array(
						'name'=>$currentChar,
						'count'=>0,
						'child'=>array()
					);
				}
				
				$currentNode = & $currentNode['child'][$currentChar];
				
				if($i===$len-1){
					$currentNode['count'] += 1;
				}
			}
		}
		
		public function aggregate($node, $prevString, & $result){
			$currentString = $prevString.$node['name'];
			if($node['count'] != 0){
				$result[$currentString] = $node['count'];
			}
			
			foreach($node['child'] as $key=>$childNode){
				$this->aggregate($childNode, $currentString, $result);
			}
			
			return $result;
		}
		
		public function dealContent($filePath){
			$content = file_get_contents($filePath);
			
			$content = preg_replace("/[^a-zA-Z']+/", ' ', $content);
			
			$content = preg_replace("/\s+$/", '', $content);
			
			return $content;
		}
	}
	
	$tree = new tree();
	
	$contentArray = explode(' ', $tree->dealContent('./sample.txt'));
	
	foreach($contentArray as $word){
		$tree->insertNode($word);
	}
	
	$result = array();
	$tree->aggregate($tree->treeRoot, '', $result);
	
	arsort($result);
	
	var_dump($result);
?>