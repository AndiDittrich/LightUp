LightUp - Markdown Parser inspired by GitHub flavored markdown (GFM)
====================================================================

Features
--------
* Written in PHP (Version > 5.3 required)
* Ultra small footprint - only 3 files required, each of them with less than 450 lines of well documented code (linewise comments)
* Extensible - you need some special features ?
* Full support for GitHub flavored markdown (excluding tables)
* Supports various syntax highlighting variants (use the highlighter of your choice)
* Released under the terms of MIT Style X11 License - you can use in any commercial case
* [Online Docs/Demo Page](http://andidittrich.de/go/lightup)

Supported Markdown Elements
---------------------------
* Hash-Style Headers (h1-h6)
* Underline-Style Headers (heading level configurable)
* Emphasis (bold, italic, strikethrough)
* Lists (Ordered, Unordered and Sublists)
* Links (Inline, text itself and reference style)
* Images (Inline and reference style)
* Indent Style Codeblocks (including hashtag language identifier for syntax highlighting)
* Fenced Codeblocks (three backticks) used by GFM
* Blockquotes (inline HTML supported)
* Inline HTML (lines which starts with an html tag are handled as html - no br-tag is added)
* Horizontal Rules (Hyphens, Asterisks or Underscores supported)
* Paragraphs automatically added after 1-line break (textblock)
* Sections automatically added around blocks with h2-headings (disabled by default, section-tag position before or after h2 tag)

Quickstart Example
------------------
This is a minimalistic example how to use LightUp within your application

```php
// use the lightup namespace
use de\andidittrich\lightup;

// include the Tokener + Parser & Helper classes
require('LightUp.php');
require('LineTokenizer.php');
require('Renderer.php');

// rendering options		
$renderingOptions = array(
	// divide content automatically into sections based on <h2> headings. the section tag is opened AFTER the <h2> heading
	'addSections' => false,
	'sectionPosition' => 'after',
	
	// allow inline html code
	'inlineHtml' => true,
	
	// use EnlighterJS syntax highlighting style for codeblocks
	'highlightingMode' => 'enlighterjs',
	
    // ==== becomes <h2>, ------ becomes <h3>
	'underlineHeading1Level' => 2,
	'underlineHeading2Level' => 3	

	// add automatically anchors before headings
	// ## Features ## becomes `<a class="anchor" id="features1" name="features"></a><h2>Features</h2>`
	'addAnchors' => true,
	'anchorClass' => 'anchor',
	
	// enable deprecated indent codeblocks
	'useIndentCodeblocks' => true,
	
	// autolink urls
	'autolinking' => true
);

// parse your markdown text
echo LightUp::render($myMDText, $renderingOptions);
```

Options
-------
The following options can be pass to the `LightUp::render` method to customize the behaviour. The default values are available in `LightUp.php::$_options` and get merged with your given options.

#### addSections
* Type: boolean
* Should the content (divided by h2 headings) automatically divided into a HTML5 `&lt;section&gt;` ?

#### sectionPosition
* Type: enum('before', 'after')
* Should the section include the heading (position before) ?

#### inlineHtml
* Type: boolean
* Is Inline-HTML allowed ?

#### underlineHeading1Level
* Type: int
* To which heading-type (h1-h6) should underline headings `=====` converted ?

#### underlineHeading2Level
* Type: int
* To which heading-type (h1-h6) should underline headings `-----` converted ?

#### addAnchors
* Type: boolean
* LightUp can automatically add anchor-links before each heading
* ## Features ## becomes `&lt;a class="anchor" id="features1" name="features"&gt;&lt;/a&gt;&lt;h2&gt;Features&lt;/h2&gt;`

#### anchorClass
* Type: string
* The CSS class of automatically added anchor-links

#### useIndentCodeblocks
* Type: boolean
* Enable/Disable deprecated inline-style codeblocks

#### autolinking
* Type: boolean
* Should URLs starting with `http://` or `https://` automatically converted into hyperlinks ?

#### highlightingMode
* Type: enum('pre', 'enlighterjs', 'shortcode', 'lighter')
* The output method which is used to handle codeblock-content - used for external syntax-highlighter

License
-------

LightUp is licensed under [The MIT License (X11)](http://opensource.org/licenses/MIT)
