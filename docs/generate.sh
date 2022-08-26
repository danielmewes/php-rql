#!/bin/bash
cat js-index.md | php8.1 jsToPhp.php > php-index.md
cat php-patches.patch | patch php-index.md
cat php-index.md | php8.1 mdToHtml.php > index.html 
