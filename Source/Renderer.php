<?php
/**
	LightUp - Markdown Renderer for GitHub flavored Markdown (GFM)
	Renders Token-List to xHTML code
	Version: 1.2
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

class Renderer{
	
	// h2 heading (section active) ?
	private $_sectionActive = false;
	
	// paragraph active ?
	private $_paragraphActive = false;
	
	// parsed token list
	private $_tokens;
	
	// rendering options
	private $_options;
	
	// current output buffer
	private $_buffer;
	
	// used headings
	private $_headings;
	
	public function __construct($options){
		$this->_options = $options;
		$this->_buffer = array();
		$this->_headings = array();
	}

	public function render($tokens){
		// store token list
		$this->_tokens = $tokens;
		
		// list of references
		$references = array();
				
		// iterate over token list
		for ($i=0;$i<count($tokens);$i++){
			// get token
			$token = $tokens[$i];
			
			// render tokens
			switch ($token['type']){
				
				// heading ?
				case 'heading':
					// close opened blocks
					$this->closeOpenedBlocks(true, ($token['level'] == 2));
					
					// open new section ?
					if ($token['level'] == 2 && $this->_options['addSections'] && $this->_options['sectionPosition'] == 'before'){
						$this->_buffer[] = '<section>';
						$this->_sectionActive = true;
					}
					
					// parse hyperlinks and other stuff
					$content = $this->parseText($token['content']);
					
					// add headingID ?
					if ($this->_options['addAnchors']){
						// generate anchor name
						$anchorName = preg_replace('/[^a-z0-9]+/', '_', strtolower($token['content']));
						
						// generate ID
						$headingID = $anchorName.count($this->_headings);
						
						// print heading
						$this->_buffer[] = sprintf('<a class="%s" id="%s" name="%s"></a><h%d>%s</h%d>', $this->_options['anchorClass'], $headingID, $anchorName, $token['level'], $content, $token['level']);
						
						// push heading to list
						$this->_headings[] = array($content, $token['level'], $headingID);						
					}else{
						// print heading
						$this->_buffer[] = sprintf('<h%d>%s</h%d>', $token['level'], $content, $token['level']);
						
						// push heading to list
						$this->_headings[] = array($content, $token['level'], '');
					}

					// open new section ?
					if ($token['level'] == 2 && $this->_options['addSections'] && $this->_options['sectionPosition'] == 'after'){
						$this->_buffer[] = '<section>';
						$this->_sectionActive = true;
					}
					break;

				// pure text node ?
				case 'text':
					// handle line breaks
					if (!$this->_paragraphActive){
						$this->_paragraphActive = true;
						$this->_buffer[] = '<p>';
					}else{
						$this->_buffer[] = '<br />';
					}
					
					// render text, parse links etc.
					$this->renderText($token);
					
					break;
					
				// inline html ?
				case 'html':
					// render text, parse links etc. like text but don't add linebreaks
					$this->renderText($token);
					break;
					
				case 'blockquote':
					$this->closeOpenedBlocks(true, false);
					$this->renderBlockquote($token);		
					break;
					
				case 'codeblock':
					$this->closeOpenedBlocks(true, false);
					$this->renderCodeblock($token);
					break;
					
				case 'horizontalRule':
					$this->closeOpenedBlocks(true, false);
					$this->_buffer[] = '<hr />';
					break;

				case 'reference':
					// store references for later replacement (complete document)
					$references[] = $token;
					break;
					
				case 'list':
					$this->closeOpenedBlocks(true, false);
					$this->renderLists($token);
					break;
					
				case 'emptyLine':
					$this->closeOpenedBlocks(true, false);
					break;
			}
			
			
		}
		
		// blocks already opened ?
		$this->closeOpenedBlocks(true, true);
		
		// render content
		$renderedContent = implode("\n", $this->_buffer);
		
		// replace references
		foreach ($references as $ref){
			// replace src reference
			$renderedContent = str_replace('REF:'.$ref['key'], $ref['value'], $renderedContent);
			
			// link reference ?
			if ($ref['title']!==false){
				$renderedContent = str_replace('REF-TITLE:'.$ref['key'], $ref['title'], $renderedContent);
			}		
		}

		return $renderedContent;
	}
	
	/**
	 * Render Lists - non recursive: sublists only supported 1 times
	 */
	private function renderLists($token){
		// tag marker
		$currentChildTag = null;
		$currentRootTag = null;

		// prerender items
		foreach ($token['items'] as $item){
			// extract item propeties
			$tagType = ($item[0] ? 'ol' : 'ul');
			$level = ($item[1] == 0 ? 1 : 2);
			$text = $this->parseText($item[2]);
						
			// init ?
			if ($currentChildTag == null && $currentRootTag == null){
				// store current tag type
				$currentRootTag =  $tagType;
				
				// open new list
				$this->_buffer[] = '<'.$tagType.'>';
				$this->_buffer[] = '<li>'.$this->parseText($text);				
				
			// root item ?
			}else if ($level == 1){
				// close opened sublist ?
				if ($currentChildTag != null){
					// close sublist
					$this->_buffer[] = '</'.$currentChildTag.'>';
					$currentChildTag = null;
				}
					
				// same type as root type ?
				if ($currentRootTag == $tagType){
					// append new node
					$this->_buffer[] = '</li><li>'.$this->parseText($text);
				}else{
					// close old list
					$this->_buffer[] = '</li></'.$currentRootTag.'>';
					
					// open new list
					$this->_buffer[] = '<'.$tagType.'>';
					$this->_buffer[] = '<li>'.$this->parseText($text);
					
					// store new tag type
					$currentRootTag = $tagType;
				}

			// sublist item ?
			}else{
				// open new sublist ?
				if ($currentChildTag == null){
					// open new list
					$this->_buffer[] = '<'.$tagType.'>';
						
					// store new tag type
					$currentChildTag = $tagType;
				}
				
				// same type as child type ?
				if ($tagType == $currentChildTag){
					// append new node
					$this->_buffer[] = '<li>'.$this->parseText($text).'</li>';
				}else{
					// close old list
					$this->_buffer[] = '</'.$currentChildTag.'>';
						
					// open new list
					$this->_buffer[] = '<'.$tagType.'>';
					$this->_buffer[] = '<li>'.$this->parseText($text).'</li>';
						
					// store new tag type
					$currentChildTag = $tagType;
				}
			}
			
		}
		
		// close opened tags
		if ($currentChildTag != null){
			$this->_buffer[] = '</'.$currentChildTag.'>';
		}
		if ($currentRootTag != null){
			$this->_buffer[] = '</li></'.$currentRootTag.'>';
		}
	}
	
	/**
	 * Render Blockquote - inner text is also rendered as textblock
	 */
	private function renderBlockquote($token){
		$this->_buffer[] = '<blockquote>';
		$this->renderText($token);
		$this->_buffer[] = '</blockquote>';
	}
	
	/**
	 * Render pure Text, parse Emphasis, Links
	 */
	private function renderText($token){
		// parse & render text
		$this->_buffer[] = $this->parseText($token['content']);
	}	
	
	/**
	 * Parse Inline Markdown
	 */
	private function parseText($text){	
		// is inline html allowed ?
		if ($this->_options['inlineHtml']===false){
			$text = htmlspecialchars($text, ENT_COMPAT | ENT_HTML5);
		}
		
		// search for bold text ** [text] **
		$text = preg_replace('/\*\*([^\*]+)\*\*/Uu', '<strong>$1</strong>', $text);
		$text = preg_replace('/__(.+)__/Uu', '<strong>$1</strong>', $text);
		
		// search for italic text * [text] *
		$text = preg_replace('/\*([^\*]+)\*/Uu', '<em>$1</em>', $text);
		$text = preg_replace('/\b_(.+)_\b/Uu', '<em>$1</em>', $text);
		
		// search for strike/deleted text
		$text = preg_replace('/~~(.+)~~/Uu', '<del>$1</del>', $text);
		
		// search for inline code
		$text = preg_replace('/`(.+)`/Uu', '<code>$1</code>', $text);
				
		// search for images (inline style)
		$text = preg_replace('/\!\[(.+)\]\((.+)\s+"(.*)"\)/Uu', '<img src="$2" alt="$1" title="$3" />', $text);
		$text = preg_replace('/\!\[(.+)\]\((.+)\)/Uu', '<img src="$2" alt="$1" />', $text);
		
		// search for images (reference style)
		$text = preg_replace_callback('/\!\[(.+)\]\[\s*(.+)\s*\]/Uu', function($matches){
			$key = sha1(strtolower($matches[2]));
			return '<img src="REF:'.$key.'" title="REF-TITLE:'.$key.'" alt="'.$matches[1].'" />';
		}, $text);
				
		// search for links (inline style with title)
		$text = preg_replace('/\[(.+)\]\((.+) "(.+)"\)/Uu', '<a href="$2" title="$3">$1</a>', $text);
		
		// search for links (inline style)
		$text = preg_replace('/\[(.+)\]\((.+)\)/Uu', '<a href="$2">$1</a>', $text);
				
		// search for links (reference style)
		$text = preg_replace_callback('/\[(.+)\]\[\s*(.*)\s*\]/Uu', function($matches){
			// use link text itself if no reference is specified
			if (strlen($matches[2])==0){
				$matches[2] = $matches[1];
			}
			$key = sha1(strtolower($matches[2]));
			return '<a href="REF:'.$key.'">'.$matches[1].'</a>';
		}, $text);
		
		// search for links (reference style without text)
		$text = preg_replace_callback('/\[(.+)\]/Uu', function($matches){
			// use link text itself
			$key = sha1(strtolower($matches[1]));
			return '<a href="REF:'.$key.'">'.$matches[1].'</a>';
		}, $text);
		
		// use autolinking ?
		if ($this->_options['autolinking']===true){
			$text = preg_replace('/(^|[^"=\'>])(https?:\/\/([\S]+)(\b|\s|$))/i', '$1<a href="$2">$2</a>', $text);
		}
	
		return $text;
	}
	
	/**
	 * Display Codeblock as pre tag or syntax highlighter style
	 */
	private function renderCodeblock($token){
		// extract content and escape tags
		$content = htmlspecialchars($token['content']);
		
		// no highlighting ?
		if ($token['lang'] === '' || $token['lang']==='no-highlight'){
			$this->_buffer[] = '<pre>'.$content.'</pre>';
			return;
		}
		
		// choose highlighting style
		switch ($this->_options['highlightingMode']){
			// enlighter style
			case 'enlighterjs':
				$this->_buffer[] = '<pre class="EnlighterJS" data-enlighter-language="'.$token['lang'].'">'.$content.'</pre>';
				break;

			// shortcode language style [html] [js] ...
			case 'shortcode':
				$this->_buffer[] = '['.$token['lang'].']'.$content.'[/'.$token['lang'].']';
				break;
				
			// pre tag - claas attribute style (used by e.g. Lighter.js)	
			case 'lighter':
				$this->_buffer[] = '<pre class="'.$token['lang'].'">'.$content.'</pre>';				
				break;
					
			// pre style fallback
			case 'pre':
			default:
				$this->_buffer[] = '<pre>'.$content.'</pre>';
				break;			
		}
	}
	
	
	private function closeOpenedBlocks($paragraph = true, $section = true){
		// close paragraph
		if ($paragraph && $this->_paragraphActive){
			$this->_buffer[] = '</p>';
			$this->_paragraphActive = false;
		}
		
		// close section
		if ($section && $this->_sectionActive){
			$this->_buffer[] = '</section>';
			$this->_sectionActive = false;
		}
	}
	
	public function getHeadings(){
		return $this->_headings;
	}
}