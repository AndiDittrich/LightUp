## Quick Reference

LightUp supports most of the GFM feature set (excluding tables) with some improvements. This page provides a simple reference how to use the extended GFM.

* John Gruber's [Original-Markdown-Specification](http://daringfireball.net/projects/markdown/)
* Inspired by the [Markdown-Cheatsheet](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet)
* Differences between GFM and tradiitional Markdown can be found [here on GitHub](https://help.github.com/articles/github-flavored-markdown)

## Table of Contents  
* [Headers](#headers)
* [Emphasis](#emphasis)
* [Links](#links)
* [Images](#images)
* [Codecode and Syntax Highlighting](#sourcecode)
* [Blockquotes](#blockquotes)
* [Lists](#lists)
* [Inline HTML](#inline_html)
* [Horizontal Rulse](#horizontal_rules)
* [Line Breaks](#line_breaks)
* [Document License](#document_license)

## Headers
Generally, Markdown provides two different expressions for headings: Hash-Style and Underline-Style

```no-highlight
Hash-Style Headings (closing hashes are optional but recommended)

# H1 Title
# H1 Title #

## H2 Title
## H2 Title ##

### H3 Title
### H3 Title ###

#### H4 Title
#### H4 Title ####

##### H5 Title
##### H5 Title #####

###### H6 Title
###### H6 Title ######

Underline-Style Headings (one or more -/= at the beginning of the next line)

Title-H1
========

Title-H2
--------

Hash-Style Heading with embedded link (anchor)

### [Syntax Highlighting](#syntax_highlighting)

With Inline-HTML

## I'm a Header <small>additional content</small>
```

Hash-Style Headings (closing hashes are optional but recommended)

# H1 Title
# H1 Title #

## H2 Title
## H2 Title ##

### H3 Title
### H3 Title ###

#### H4 Title
#### H4 Title ####

##### H5 Title
##### H5 Title #####

###### H6 Title
###### H6 Title ######

Underline-Style Headings (one or more -/= at the beginning of the next line)

Title-H1
========

Title-H2
--------

Hash-Style Heading with embedded link (anchor)

### [Advanced Syntax Highlighting](#syntax_highlighting)

With Inline-HTML

### I'm a Header <small>additional content</small>

## Emphasis

```no-highlight
#### Italics
Single *asterisks* or _underscores_ at the beginning+end of a word.
Compared to classic Markdown, multiple_underscores_in_words are ignored to avoid collisions with code and names which often contain underscores within.

#### Strong/Bold
Double **asterisks** or __underscores__ at the beginning+end of a word.

#### Strikethrough
Use double tildes at the beginning+end of a word. ~~You don't want to read this~~

#### Combined
Combined emphasis with **asterisks and _underscores_ or ~~some stuff~~**.
```

#### Italics
Single *asterisks* or _underscores_ at the beginning+end of a word.
Compared to classic Markdown, multiple_underscores_in_words are ignored to avoid collisions with code and names which often contain underscores within.

#### Strong/Bold
Double **asterisks** or __underscores__ at the beginning+end of a word.

#### Strikethrough
Use double tildes at the beginning+end of a word. ~~You don't want to read this~~

#### Combined
Combined emphasis with **asterisks and _underscores_ or ~~some stuff~~**.


## Links

With GFM, you have three possibilities to create hyperlinks

* Markdown standard inline links
* GFM style reference links
* GFM URL-Autolinking

```no-highlight
Standard link style: [GitHub](http://www.github.com)

Standard link style with anchor-links: [Table of Contents](#table_of_contents)

Standard link style with title [<3 Git](http://www.github.com "WE LOVE GIT")

[Reference style][some unique reference text]

You can also use [numbers for reference links][1]
Like [Google+][2] ?
Love [Tweets][3] ?

Or just use [our link text itself]

Lorem ipsum dolor sit amet, 
consetetur sadipscing elitr, 
sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
sed diam voluptua. 

[some unique reference text]: https://developer.mozilla.org/de/
[our link text itself]: https://www.flickr.com
[1]: https://www.facebook.com
[2]: https://plus.google.com
[3]: https://twitter.com
```

Standard link style: [GitHub](http://www.github.com)

Standard link style with anchor-links: [Table of Contents](#table_of_contents)

Standard link style with title [<3 Git](http://www.github.com "WE LOVE GIT")

[Reference style][some unique reference text]

You can also use [numbers for reference links][1]
Like [Google+][2] ?
Love [Tweets][3] ?

Or just use [our link text itself]

Lorem ipsum dolor sit amet, 
consetetur sadipscing elitr, 
sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
sed diam voluptua. 

[some unique reference text]: https://developer.mozilla.org/de/
[our link text itself]: https://www.flickr.com
[1]: https://www.facebook.com
[2]: https://plus.google.com
[3]: https://twitter.com


## Images

Using images is as easy as adding hyperlinks.

```no-highlight
Inline style image (without title)
![alt image text](http://lightup.andidittrich.de/Resources/php-logo.jpg)

Inline style image (with title)
![alt image text](http://lightup.andidittrich.de/Resources/php-logo.jpg "Elephpant")

Reference style image (with title)
![alt image text][special image reference with title]

Lorem ipsum dolor sit amet, 
consetetur sadipscing elitr, 
sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
sed diam voluptua. 

[special image reference with title]: http://lightup.andidittrich.de/Resources/php-logo.jpg "PHP powers the web"
```

Inline style image (without title)
![alt image text](http://lightup.andidittrich.de/Resources/php-logo.jpg)

Inline style image (with title)
![alt image text](http://lightup.andidittrich.de/Resources/php-logo.jpg "Elephpant")

Reference style image (with title)
![alt image text][special image reference with title]

Lorem ipsum dolor sit amet, 
consetetur sadipscing elitr, 
sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
sed diam voluptua. 

[special image reference with title]: http://lightup.andidittrich.de/Resources/php-logo.jpg "PHP powers the web"

## Sourcecode
LightUp processes codeblocks with indent style (extended) as well as GFMs fenced codeblocks with three back-ticks <code>```</code>. 
Syntax Highlighting is **not provided by LightUp** directly - it includes support for a several syntax highlighter like [EnlighterJS](http://andidittrich.de/go/enlighterjs).

### Inline Code

```no-highlight
You have to surround your `code` with backticks.
```

You have to surround your `code` with backticks.

### Indent Codeblocks

Indent-Style Codeblocks require a 4-space indent at the beginning of each line. Markdown as well as GFM doesn't support syntax highlighting for these type of codeblocks.
LightUp instead supports hashtag language identifiers (e.g. used by [MooTools Forge](http://mootools.net/forge/)).

```no-highlight
#### Indent Style Codeblock (no syntax highlighting available)
    /**
     * When this gets called, it sends a message to the interpreter.
     * The interpreter usually shows it on the command prompt (For Windows users)
     * or the terminal (For *nix users).(Assuming it's open)
     */
    private void calculate() {
    	if ((this.input % 2) == 0) {
    		JOptionPane.showMessageDialog(null, "Even");
    	}else{
    		JOptionPane.showMessageDialog(null, "Odd");
    	}
    }

#### Indent Style Codeblock with Hashtag language identfier
    #javascript
    // create new alias manager instance
    this.aliasManager = new EnlighterJS.Alias(options);
    
    // initialize compiler
    if (EnlighterJS.Compiler[this.options.compiler]){
    	this.compiler = new EnlighterJS.Compiler[this.options.compiler](options);
    }else{
    	this.compiler = new EnlighterJS.Compiler.List(options);
    }
```    

#### Indent Style Codeblock (no syntax highlighting available)
    /**
     * When this gets called, it sends a message to the interpreter.
     * The interpreter usually shows it on the command prompt (For Windows users)
     * or the terminal (For *nix users).(Assuming it's open)
     */
    private void calculate() {
    	if ((this.input % 2) == 0) {
    		JOptionPane.showMessageDialog(null, "Even");
    	}else{
    		JOptionPane.showMessageDialog(null, "Odd");
    	}
    }

#### Indent Style Codeblock with Hashtag language identfier
    #javascript
    // create new alias manager instance
    this.aliasManager = new EnlighterJS.Alias(options);
    
    // initialize compiler
    if (EnlighterJS.Compiler[this.options.compiler]){
    	this.compiler = new EnlighterJS.Compiler[this.options.compiler](options);
    }else{
    	this.compiler = new EnlighterJS.Compiler.List(options);
    }

### Fenced Codeblocks (GFM Style)
I recommmend to use GFM`s fenced codeblocks **only**. This is the most efficient way to produce tidy and interoperable GFM documents!

```
  #### Generic Code
  Fenced Codeblocks without a language identifier will be displayed unhighligted as `&lt;pre&gt;` element
  ```
  + Hello Code
  - Hello World
  ```

  #### Highlighted PHP Code
  Codeblocks with a language identifier will be highlighted by the configurated method ([EnlighterJS](http://andidittrich.de/go/enlighterjs) in this Example).

  ```php
  // you should use your prefered autoloading method
  require('Source/LightUp.php');
  require('Source/LineTokenizer.php');
  require('Source/Renderer.php');

  // some LightUp options
  $options = array(
  	'highlightingMode' => 'enlighterjs'	
  );

  // get the demo content
  $demo1Raw = file_get_contents('Resources/QuickReference.md');
  
  // render markdown as html
  $pageContent = LightUp::render($demo1Raw, $options);

  // and display it within the template-file
  include('Resources/BootstrapTemplate.phtml');
  ```
```
#### Generic Code
Fenced Codeblocks without a language identifier will be displayed unhighligted as `&lt;pre&gt;` element
```
+ Hello Code
- Hello World
```

#### Highlighted PHP Code
Codeblocks with a language identifier will be highlighted by the configurated method ([EnlighterJS](http://andidittrich.de/go/enlighterjs) in this Example).

```php
// you should use your prefered autoloading method
require('Source/LightUp.php');
require('Source/LineTokenizer.php');
require('Source/Renderer.php');

// some LightUp options
$options = array(
	'highlightingMode' => 'enlighterjs'	
);

// get the demo content
$demo1Raw = file_get_contents('Resources/QuickReference.md');

// render markdown as html
$pageContent = LightUp::render($demo1Raw, $options);

// and display it within the template-file
include('Resources/BootstrapTemplate.phtml');
```

## Blockquotes

Blockquotes can be used by adding the "greater" sign (&gt;) at the beginning of each line. You can also use emphasis and inline-html within!

```no-highlight
> Lorem ipsum dolor sit amet,
> consetetur [sadipscing](https://google.com) elitr,
> sed **diam nonumy eirmod** tempor invidunt ut labore et dolore magna aliquyam erat,
> sed diam voluptua.

Next blockquote element

> At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. 
```

> Lorem ipsum dolor sit amet,
> consetetur [sadipscing](https://google.com) elitr,
> sed **diam nonumy eirmod** tempor invidunt ut labore et dolore magna aliquyam erat,
> sed diam voluptua.

Next blockquote element

> At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. 

## Lists

```no-highlight
### Ordered Lists
Ordered list items starts with an number followed by a dot and space (number-dot-space-content).
**Notice:** there is a difference between original Markdown and LightUp how paragraphs and additional content within list-items are processed

1. First Item
2. Second Item
3. Third item
  * Unordered sublist (2 space indent)
  * With a second item
4. Fourth Item (the numbers don't matter, only the item ordering)
  And this is some text added to the list item
  Another Line
Some text outside the list (not indented)

### Unordered Lists
The line starts with an asterisks, a minus or plus (mixes are allowed)
 
* Asterisks Item 1
- Minus Item 2
+ Plus Item 3

* New
* List
* Block
```

### Ordered Lists
Ordered list items starts with an number followed by a dot and space (number-dot-space-content).
**Notice:** there is a difference between original Markdown and LightUp how paragraphs and additional content within list-items are processed

1. First Item
2. Second Item
3. Third item
  * Unordered sublist (2 space indent)
  * With a second item
4. Fourth Item (the numbers don't matter, only the item ordering)
  And this is some text added to the list item
  Another Line
Some text outside the list (not idented)

### Unordered Lists
The line starts with an asterisks, a minus or plus (mixes are allowed)
 
* Asterisks Item 1
- Minus Item 2
+ Plus Item 3

* New
* List
* Block

## Inline HTML

You can optionally use inline HTML in your Markdown (if enabled) - sometimes it might be very usefull.
Compared to the GitHub Markdown-Parser, LightUp does a much better job here and allows also emphasis within inline-html tags! 
**If a line starts with an opening html-tag, no lineabreak is added!** This feature allows you to use raw-html blocks within your Markdown-Sheet.

```no-highlight
### Special Title <small>with subheading</small>

<dl>
  <dt>Definition Title</dt>
  <dd>Entry 1</dd>
	
  <dt>Definition Title</dt>
  <dd>Entry 2, some **emphasis** used _within_ a **_html-tag_**</dd>	

  <dt>Notice</dt>
  <dd>~~Try it on GitHub~~</dd>
</dl>
```
### Special Title <small>with subheading</small>

<dl>
  <dt>Definition Title</dt>
  <dd>Entry 1</dd>

  <dt>Definition Title</dt>
  <dd>Entry 2, some **emphasis** used _within_ a **_html-tag_**</dd>

  <dt>Notice</dt>
  <dd>~~Try it on GitHub~~</dd>
</dl>

## Horizontal Rules

```
Three or more (...) at the beginning of a line.
Empty lines before and after are recommended when using hyphens to avoid collisions with Underline-Style Headings.


Three or more Hyphens

---

Three or more Underscores

________

Three or more Asterisks

********************************

Combined Hyphens/Underscores/Asterisks are ignored
**********----________
```

Three or more (...) at the beginning of a line.
Empty line before and after are recommended when using hyphens to avoid collisions with Underline-Style Headings.


Three or more Hyphens

---

Three or more Underscores

________

Three or more Asterisks

********************************

Combined Hyphens/Underscores/Asterisks are ignored
**********----________

## Line Breaks

In original Markdown, line-breaks may confuse some people - therefore GFM provides a much more consistent behaviour
* Each line will be automatically terminated with a break &lt;br /&gt;
* Put one or more empty lines into your content to open a new paragraph (no break will be added)
* Lines which starts with a HTML Tag will **not** be terminated with a break (LightUp improvement)

```no-highlight
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.

Lorem ipsum dolor sit amet, 
consetetur sadipscing elitr, 
sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
sed diam voluptua. 

At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
```

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.

Lorem ipsum dolor sit amet, 
consetetur sadipscing elitr, 
sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
sed diam voluptua. 

At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.

## Document License
This Markdown Document (as well as LightUp itself) is released under the Terms of [MIT X11 License](License.md). Feel free to use it for your own purpose.