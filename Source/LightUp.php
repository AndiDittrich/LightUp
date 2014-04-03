<?php
/**
	LightUp - Markdown Renderer for GitHub flavored Markdown (GFM)
	Helper class to easy parse&render a GFM (markdown) document with 1 method call
	Version: 1.1
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://andidittrich/go/lightup
	License: MIT X11-License
	
	Copyright (c) 2013-2014, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace de\andidittrich\lightup;

class LightUp{
	
	// default options
	public static $_options = array(
			'addSections' => false,
			'sectionPosition' => 'after',
			'inlineHtml' => true,
			'highlightingMode' => 'pre',
			'underlineHeading1Level' => 1,
			'underlineHeading2Level' => 2,
			'addAnchors' => true,
			'anchorClass' => 'anchor',
			'useIndentCodeblocks' => true,
			'autolinking' => true
	);
	
	/**
	 * Renders a markdown document (github flavored markdown style)
	 * @param String $text Markdown document texxt to render as HTML
	 * @param Array $options (optional) rendering options - if no options give, the default values are used
	 * @return String rendered HTML document
	 */
	public static function render($text, $options=array()){
		// merge options
		foreach (self::$_options as $key => $value){
			if (!array_key_exists($key, $options)){
				$options[$key] = $value;
			}
		}
		
		// create new tokenizer instance
		$tokenizer = new LineTokenizer($options);
		
		// create new renderer instance
		$renderer = new Renderer($options);
		
		// parse text, convert it into a token list
		$tokens = $tokenizer->getTokens($text);

		// render tokens and return html
		return $renderer->render($tokens);
	}
	
	
}