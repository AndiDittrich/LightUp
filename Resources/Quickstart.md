This is a minimalistic example how to use **LightUp** (see Quickstart.php sources)
And..that's it! You can also customize the rendering behaviour by passing an options-array to the `LightUp::render($content, $options)` method.

```php
// basic namespace
use de\andidittrich\lightup\LightUp;

// you should use your prefered autoloading method
require('Source/LightUp.php');
require('Source/LineTokenizer.php');
require('Source/Renderer.php');

// get the demo content
$content = file_get_contents('Resources/Quickstart.md');

// render markdown as html
echo LightUp::render($content);
```