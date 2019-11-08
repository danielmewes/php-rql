#!/bin/bash
cat js-index.md | php jsToPhp.php > php-index.md
cat php-patches.patch | patch php-index.md
cat php-index.md | php mdToHtml.php > index.html
