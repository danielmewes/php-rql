<?php

/* Reads markdown containing code snippets for the JavaScript driver and attempts to convert them to code snippets for PHP-RQL.
   This doesn't always work, but gives a nicer basis to start off from.
   Uses jTokenizer by Tim Whitlock http://timwhitlock.info/blog/2009/11/jparser-and-jtokenizer-released/*/

require_once 'jparser-libs/jtokenizer.php';

// From token_highlight example
function _j_token_is_word( array $token ){
	$Lex = JLex::singleton();
	return is_array($token) && $Lex->is_word($token[1]);
}

function isPunctation($token) {
    list( $t, $s, $l, $c ) = $token;
    return !is_int($t) && $s === $t;
}

function convertExample($js, $inApiBody = false) {
    @$tokens = j_token_get_all($js);
    
    // Based on the token_highlight example code
    $lines = array('');
    $line =& $lines[0];
    $inFunction = false;
    $objectsOpenInFunction = 1;
    for ($i = 0; $i < count($tokens); ++$i) {
        $token = $tokens[$i];
        list( $t, $s, $l, $c ) = $token;
        @$next = $tokens[$i+1];
	    list( $tn, $sn, $ln, $cn ) = $next;
	    @$prev = $tokens[$i-1];
	    list( $tp, $sp, $lp, $cp ) = $prev;
	    
	    if(_j_token_is_word($token)) {
	        // Token is a keyword
	        if ($s == "function") {
	            $inFunction = true;
	            $objectsOpenInFunction = 0; // Only works as long as we don't have nested functions
	        }
	    }
	    else if(isPunctation($token)){
	        if ($s == ":") $s = " =>";
		    else if ($s == ".") {
		        if ($sp == "r") {
		            $s = "\\";
		        } else if ($sp == "." || $sn == ".") {
		            // Probably part of a ... place holder
		        } else {
		            $s = "->";
		        }
		    }
		    // apibody uses [ ] to mark optional arguments
		    else if (!$inApiBody && $s == "[") $s = "array(";
		    else if (!$inApiBody && $s == "]") $s = ")";
		    else if ($s == "{") {
		        if (!$inFunction || $objectsOpenInFunction != 0) {
    		        $s = "array(";
    		    }
    		    $objectsOpenInFunction += 1;
		    }
		    else if ($s == "}") {
		        $objectsOpenInFunction -= 1;
		        if (!$inFunction || $objectsOpenInFunction != 0) {
    		        $s = ")";
    		    } else {
    		        $inFunction = false;
    		    }
		    }
		    else if ($s == "(" && isPunctation($prev) && $sp == ")") {
		        // Two function calls next to each other are not supported in PHP. I assume we should use getField instead.
		        $s = "->getField(";
		    }
	    }
	    else {
	        if ($t == 2) {
	            // symbol
	            if (isPunctation($next) && $sn == ":") {
	                // We're probably inside an object. Make this symbol a string for PHP
	                $s = "'$s'";
	            } else if (!(isPunctation($next) && $sn == "(") && $s != "r") {
	                // This is not a function call, so probably this is a variable. Add a dollar sign.
	                if (!$inApiBody) {
    	                $s = '$' . $s;
    	            }
	            }
	        }
	    }
	    // style and push onto source code lines array
	    while(isset($s{0})){
		    if(!preg_match('/^(.*)(\n|\r\n|\r)/', $s, $r)){
			    $lines[0] .= $s;
			    break;
		    }
		    $lines[0] .= $r[1];
		    array_unshift( $lines, '' );
		    $s = substr( $s, strlen($r[0]) );
	    }
    }
    $lines = array_reverse($lines);

    $output = '';
    foreach ($lines as $l) {
        $output .= $l . "\n";
    }
    $output = substr($output, 0, -1);

    // Post-processing filters:
    if ($inApiBody) {
        $output = str_replace(', ]callback', "]", $output);
        $output = str_replace(', callback', "", $output);
        $output = str_replace('callback', "", $output);
    } else {
        $output = str_replace(', $callback', "", $output);
        $output = str_replace('$callback', "", $output);
    }
    
    return $output;
}

$input = file_get_contents('php://stdin');

// Replace code snippets:
$output = preg_replace_callback('|^```js((.*\n)*?)```|m', function($matches) {
    return "```php" . convertExample($matches[1]) . "```";
}, $input);
// Also replace API body:
$output = preg_replace_callback('|^{% apibody %}((.*\n)*?){% endapibody %}|m', function($matches) {
    return "{% apibody %}" . preg_replace_callback('|(.*)(( &rarr){0,1}.*)|', function($imatches) {
        return convertExample($imatches[1], true) . $imatches[2];
    }, $matches[1]) . "{% endapibody %}";
}, $output);

// Replace optarg names, since we use under_score rather than camelCase in PHP-RQL
$output = str_replace("includeStates", "include_states", $output);
$output = str_replace("returnChanges", "return_changes", $output);
$output = str_replace("nonAtomic", "non_atomic", $output);
$output = str_replace("useOutdated", "use_outdated", $output);
$output = str_replace("leftBound", "left_bound", $output);
$output = str_replace("rightBound", "right_bound", $output);
$output = str_replace("defaultTimezone", "default_timezone", $output);
$output = str_replace("geoSystem", "geo_system", $output);
$output = str_replace("numVertices", "num_vertices", $output);
$output = str_replace("primaryReplicaTag", "primary_replica_tag", $output);
$output = str_replace("dryRun", "dry_run", $output);
$output = str_replace("waitFor", "wait_for", $output);

$output = str_replace("RqlRuntimeError", "RqlServerError", $output);

echo $output;

?>
