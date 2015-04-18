<?php

include('php-markdown/Michelf/MarkdownExtra.inc.php');

$input = file_get_contents('php://stdin');

// Pre-filtering: Remove things we aren't supporting yet
$input = preg_replace('|\[Read more about this command &rarr;\].*|', "", $input);
$input = preg_replace_callback('|## \[(.*)\].* ##|', function($matches) { return '## ' . $matches[1] . ' ##'; }, $input);
$input = preg_replace('|^---(.*\n)*---$|m', "", $input);
$input = preg_replace_callback('|\{% apibody %\}((.*\n)*?)\{% endapibody %\}|m', function($matches) { return str_replace('>', '&gt;', str_replace('<', '&lt;', $matches[0])); }, $input);

$output = \Michelf\MarkdownExtra::defaultTransform($input);
$output = str_replace('<h2>', '<h3>', $output);
$output = str_replace('</h2>', '</h3>', $output);
$output = preg_replace_callback('|<p>\{% apibody %\}.*\n((.*\n)*?).*\{% endapibody %\}</p>|', function ($matches) { return '<pre class="syntax"><code class="syntax">' . $matches[1] . '</code></pre>'; }, $output);
$output = preg_replace_callback('|<pre>(<code class="php">((.*\n)*?).*\</code></pre>)|', function ($matches) { return '<pre class="example">' . $matches[1]; }, $output);
$sections = array();
$output = preg_replace_callback('|\{% apisection (.*) %\}|', function($matches) use(&$sections) { $sections[] = $matches[1]; return '<h2 id="' . urlencode($matches[1]) . '">' . $matches[1] . '</h2>'; }, $output);
$output = str_replace('{% endapisection %}', '', $output);

echo '<html><head><link rel="stylesheet" type="text/css" href="api.css"></head><body>';
echo '<h1>PHP-RQL API reference</h1>';
echo '<h2 id="Index">Index</h2>';
echo '<ul>';
foreach ($sections as $s) {
    echo '<li><a href="#' . urlencode($s) . '">' . $s . '</a></li>';
}
echo '</ul>';
echo $output;
echo '</body></html>';

?>
