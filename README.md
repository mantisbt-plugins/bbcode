# BBCode plugin for MantisBT

## Description
It's help convert some bbcode to html-style.
For highlighting the source use the MantisBT plugin [highlightcode](https://github.com/mantisbt-plugins/highlightcode)

## Screenshot
![Screenshot on a mantisbt install](https://raw.github.com/bueltge/bbcode/master/screenshot-1.png)

### Supported BBCode
```
	[b]            => <strong>
	[i]            => <em>
	[u]            => <span style="text-decoration: underline;">
	[del]          => <span style="text-decoration: line-through;">
	[sub]          => <sub>
	[sup]          => <sup>
	[tt]           => <tt>
	[img]          => <img>
	[url]          => <a href>
	[left]         => <div align="left">
	[right]        => <div align="right">
	[center]       => <center>
	[hr]           => <hr>
	[color=#333]   => <span style="color: #333;">
	[code]         => <pre><code>
	[quote=foobar] => <div style="border: solid #c0c0c0 1px; padding: 0 10px 10px 10px; background-color: #d8d8d8"><i>foobar wrote</i><br><br>your text</div>
	[quote]        => <div style="border: solid #c0c0c0 1px; padding: 0 10px 10px 10px; background-color: #d8d8d8"><i>Someone wrote</i><br><br>your text</div>
```

## Installation
 1. Just unpack (with folder BBCode) in MantisBT_Root_Folder/plugins/
 2. Install also the [highlightcode](https://github.com/mantisbt-plugins/highlightcode) plugin
 3. Go /manage_plugin_page.php
 4. Install MantisBT BBCode Plugin
 5. Use it ;)

## Other Notes
### Version
Version see in VERSION.txt

### Licence
Good news, this plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog.

### Contributors
see the [contributors graph](https://github.com/mantisbt-plugins/bbcode/graphs/contributors) for the current status
