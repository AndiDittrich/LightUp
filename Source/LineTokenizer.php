<?php
/**
	LightUp - Markdown Line-Tokenizer for GitHub flavored Markdown (GFM)
	Converts a GFM (Markdown) document string into a token list
	Version: 1.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://andidittrich.de/go/lightup
	License: MIT X11-License
	
	Copyright (c) 2013-2014, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace de\andidittrich\lightup;

class LineTokenizer{
	
	// token list
	private $_tokens;
	
	// rendering options
	private $_options;
	
	// codeblock active ?
	private $_backtickCodeblockActive = false;
	private $_indentCodeblockActive = false;
		
	public function __construct($option){
		$this->_options = $option;
		$this->_tokens = array();
	}

	// render wp content as markdown 
	public function getTokens($text){
		
		// linewise processing
		$lines = explode("\n", $text);
		
		// iterate over lines
		// WARNING: the processing order is required !! DO NOT CHANGE IT !!
		foreach ($lines as $line){

			// codeblock open/close tags - skip if other codeblock style is currently active!
			if (!$this->_indentCodeblockActive && $this->processBacktickCodeblock($line)){
				continue;
			}
			
			// indent style codeblocks
			if ($this->processIndentCodeblocks($line)){
				continue;				
			}
				
			// blockquoted line
			if ($this->processBlockquotes($line)){
				continue;
			}
			
			// process html
			if ($this->processHtml($line)){
				continue;
			}
			
			// horizontal rules
			if ($this->processHorizontalRules($line)){
				continue;
			}
						
			// hash-style headings
			if ($this->processHashStyleHeading($line)){
				continue;
			}
			
			// underline style headings
			if ($this->processUnderlineStyleHeading($line)){
				continue;
			}
			
			// reference
			if ($this->processReferences($line)){
				continue;
			}
			
			// lists
			if ($this->processLists($line)){
				continue;
			}
			
			// empty line
			if ($this->processEmptyLines($line)){
				continue;
			}
			
			// no special action - create text token
			$this->_tokens[] = array(
				'type' => 'text',
				'content' => trim($line)		
			);
		}		
		
		return $this->_tokens;
	}
	

	
	/**
	 * Line starts with list indicator ?	
	 */
	private function processLists($line){

		// unordered list ?
		if (preg_match('/^(\s*)[\*\+\-]\s+(.+)$/Uu', $line, $matches) === 1){
			// indent (sublist) ?
			$sublist = strlen($matches[1]) > 0;
			
			// is last token a list item ?
			if (count($this->_tokens) > 1 && $this->_tokens[count($this->_tokens)-1]['type']=='list'){
				// push token to list
				$this->_tokens[count($this->_tokens)-1]['items'][] = array(false, $sublist, $matches[2]);				
			}else{
				// no previous list -> generate list token
				$this->_tokens[] = array(
						'type' => 'list',
						'items' => array(array(false, $sublist, $matches[2]))
				);
			}			
			
			return true;
		
			// ordered list ?
		}else if (preg_match('/^(\s*)[0-9]+\.\s+(.+)$/Uu', $line, $matches) === 1){
			// indent (sublist) ?
			$sublist = strlen($matches[1]) > 0;
				
			// is last token a list item ?
			if (count($this->_tokens) > 1 && $this->_tokens[count($this->_tokens)-1]['type']=='list'){
				// push token to list
				$this->_tokens[count($this->_tokens)-1]['items'][] = array(true, $sublist, $matches[2]);
			}else{
				// no previous list -> generate list token
				$this->_tokens[] = array(
						'type' => 'list',
						'items' => array(array(true, $sublist, $matches[2]))
				);
			}
				
			return true;
					
		}else{
			// is last token a list item ?
			if (count($this->_tokens) > 1 && $this->_tokens[count($this->_tokens)-1]['type']=='list'){
				// is line indented ?
				if (preg_match('/^(\s+)(.+)$/Uu', $line, $matches) === 1){
					// get last list item index
					$last = count($this->_tokens[count($this->_tokens)-1]['items'])-1;
					
					// append content to last list item
					$this->_tokens[count($this->_tokens)-1]['items'][$last][2] .= "\n<br />". $matches[2];
					
					return true;
				}
			}

			return false;
		}
	}
	
	/**
	 * Line start with html tag ?
	 * add special token to handle line-breaks <br /> correctly
	 */
	private function processHtml($line){
		if (preg_match('/\s*\<[A-Za-z]+/AUu', $line, $matches) === 1){
			// generate html inline token
			$this->_tokens[] = array(
					'type' => 'html',
					'content' => $line
			);
	
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Empty Line found ?
	 */
	private function processEmptyLines($line){
		if (strlen(trim($line))==0){
			// only 1 new line is ok
			if (count($this->_tokens) > 1 && $this->_tokens[count($this->_tokens)-1]['type']!='emptyLine'){
				$this->_tokens[] = array(
						'type' => 'emptyLine'
				);
			}
			
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Horizontal Rule found ?
	 */
	private function processHorizontalRules($line){
		// process rules - min 3 chars of * _ - required; without any other chars
		if (preg_match('/^\s*\-{3,}|\_{3,}|\*{3,}\s*$/u', $line, $matches) === 1){
			// empty line before ?
			if ($this->_tokens[count($this->_tokens)-1]['type']=='emptyLine'){
				// generate heading
				$this->_tokens[] = array(
					'type' => 'horizontalRule'
				);
		
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * Document Reference found ?
	 */
	private function processReferences($line){
		// process image references
		if (preg_match('/^\[(.+)\]\:\s*(.+)\s+\"(.+)\"\s*$/u', $line, $matches) === 1){
			// generate heading
			$this->_tokens[] = array(
					// get heading level
					'type' => 'reference',
					'key' => sha1(strtolower($matches[1])),
					'value' => $matches[2],
					'title' => $matches[3]
			);
	
			return true;
			
		// process references
		}else if (preg_match('/^\[(.+)\]\:\s*(.+)\s*$/u', $line, $matches) === 1){
			// generate heading
			$this->_tokens[] = array(
					// get heading level
					'type' => 'reference',
					'key' => sha1(strtolower($matches[1])),
					'value' => $matches[2],
					'title' => false
			);
	
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Blockquote Line found ?
	 */
	private function processBlockquotes($line){
		// process #-style headings
		if (preg_match('/^\s*\>\s*(.*)$/u', $line, $matches) === 1){
			// blockquote already active (last token)?
			if (count($this->_tokens) > 1 && $this->_tokens[count($this->_tokens)-1]['type']=='blockquote'){
				// append data
				$this->_tokens[count($this->_tokens)-1]['content'] .= '<br />' . $matches[1];
			}else{
				// generate new blockquote token
				$this->_tokens[] = array(
						// get heading level
						'type' => 'blockquote',
						'content' => $matches[1]
				);
			}
				
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Line Starts with 4 spaces (or 1 tab) ?
	 */
	private function processIndentCodeblocks($line){
		// option enabled ?
		if ($this->_options['useIndentCodeblocks'] !== true){
			return false;
		}
		
		// codeblock language hash-heading start ?
		if (preg_match('/^(    |\t)#(.+)$/Uu', $line, $matches) === 1){
			// create new codeblock token
			$this->_tokens[] = array(
					'type' => 'codeblock',
					'lang' => $matches[2],
					'content' => ''
			);
				
			// set flag
			$this->_indentCodeblockActive = true;
				
			return true;
		}else if (preg_match('/^(    |\t)(.*)$/Uu', $line, $matches) === 1){
			// codeblock currently active ?
			if ($this->_indentCodeblockActive){
				// append data
				$this->_tokens[count($this->_tokens)-1]['content'] .= $matches[2] . "\n";
			}else{
				// create new codeblock token
				$this->_tokens[] = array(
						'type' => 'codeblock',
						'lang' => '',
						'content' => $matches[2]
				);				
			}
			
			// set flag
			$this->_indentCodeblockActive = true;
			
			return true;
		}else{
			// reset flag
			$this->_indentCodeblockActive = false;
			
			return false;
		}
	}
	
	/**
	 * Codeblock found ?
	 */
	private function processBacktickCodeblock($line){
		// codeblock active ?
		if ($this->_backtickCodeblockActive){
			// codeblock end ?
			if (preg_match('/^```\s*$/u', $line, $matches) === 1){
				$this->_backtickCodeblockActive = false;
			}else{
				// append data
				$this->_tokens[count($this->_tokens)-1]['content'] .= $line . "\n";
			}
			return true;
		}
		
		// process github-style codeblocks
		if (preg_match('/^```(.*)\s*$/Uu', $line, $matches) === 1){
			// generate codeblock start
			$this->_tokens[] = array(
					// get heading level
					'type' => 'codeblock',
					'lang' => $matches[1],
					'content' => ''
			);

			// set flag
			$this->_backtickCodeblockActive = true;
			
			return true;
			
		}else{
			return false;
		}
	}
	
	/**
	 * Legacy Headings found ?
	 */
	private function processUnderlineStyleHeading($line){
		// process double underline-style headings - convert it to hash style tokens
		if (preg_match('/^[\=]+\s*$/u', $line, $matches) === 1){
			// get last token
			$lastToken = array_pop($this->_tokens);
			
			// check for previous text token
			if ($lastToken && $lastToken['type']=='text'){
				// convert token
				$this->_tokens[] = array(
					'type' => 'heading',
					'content' => trim($lastToken['content']),
					'level' => $this->_options['underlineHeading1Level']	
				);
			}else{
				// push token back to list
				$this->_tokens[] = $lastToken;
			}
			
			return true;
			
		// handle single underline	
		}else if (preg_match('/^[\-]+\s*$/u', $line, $matches) === 1){
			// get last token
			$lastToken = array_pop($this->_tokens);
				
			// check for previous text token
			if ($lastToken && $lastToken['type']=='text'){
				// convert token
				$this->_tokens[] = array(
					'type' => 'heading',
					'content' => trim($lastToken['content']),
					'level' => $this->_options['underlineHeading2Level']	
				);
			}else{
				// push token back to list
				$this->_tokens[] = $lastToken;
			}
			
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Hash Style Heading found ?
	 */
	private function processHashStyleHeading($line){
		// process #-style headings
		if (preg_match('/^\s*(#+)\s*(.+)\s*$/u', $line, $matches) === 1){
			$level = strlen($matches[1]);
			
			// remove trailing hashes (fault tolerant)
			$content = rtrim($matches[2], '#');
			
			// generate heading
			$this->_tokens[] = array(
				'type' => 'heading',
				'level' => $level,	
				'content' => trim($content)		
			);
			
			return true;
		}else{
			return false;
		}
	}
	
}