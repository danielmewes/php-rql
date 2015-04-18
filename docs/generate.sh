#!/bin/bash
cat js-index.md | php5 jsToPhp.php > php-index.md
cat php-patches.patch | patch php-index.md
cat php-index.md | php5 mdToHtml.php > index.html 
