<?php
/**
 * Parse a .proto file and generates the classes in a file
 * @author Nikolai Kordulla
 *
 * This version is stripped down to generate only enum constants.
 * It's not a fully functional Protobuf parser anymore.
 */
class PBParser
{
    // the message types array of (field, param[]='repeated,required,optional')
    var $m_types = array();

    // namespace
    var $namespace = null;
    var $namespaceStr = "\\";

    // different types
    var $scalar_types = array('double' => 'PBDouble', 'float' => 'PBDouble', 'int32' => 'I_PBInt', 'int64' => 'I_PBInt',
                              'uint32', 'uint64', 'sint32' => 'PBSignedInt', 'sint64' => 'PBSignedInt',
                              'fixed32', 'fixed64', 'sfixed32', 'sfixed64',
                              'bool' => 'I_PBBool', 'string' => 'I_PBString', 'bytes' => 'I_PBString', 'i_pbenum' => 'I_PBEnum');

    /**
     * parses the profile and generates a filename with the name
     * pb_proto_[NAME]
     * @param String $protofile - the protofilename with the path
     */
    public function parse($protofile, $namespace = null, $dir = '')
    {
        $string = file_get_contents($protofile);
        // now take the filename
        //$filename = str_replace("\\", "/", $filename);
        $filename = explode("/", $protofile);
        $filename = $filename[count($filename) - 1];
        // strip the comments out of the protofile
        $this->_strip_comments($string);
        $string = trim($string);
        $this->_parse_message_type($string, '');
        // now create file with classes
        $name = explode('.', $filename);
        array_pop($name);
        $name = join($name, '.');
        $this->namespace = $namespace;
        if (isset($namespace)) {
            $this->namespaceStr = "\\$namespace\\";
        } else {
            $this->namespaceStr = "\\";
        }

        if ($dir) {
            $this->dir = rtrim($dir, '/') . '/';
        }

        $this->_create_class_file('pb_proto_' . $name . '.php');
    }

    private function toCamelCase($s)
    {
        // Snippet to get CamelCase from php-protobuf
        $chunks = preg_split('/[^a-z0-9]/is', $s);
        return implode('', array_map('ucfirst', $chunks));
        //
    }

    /**
     * Creates php class file for the proto file
     *
     * @param String $filename - the filename of the php file
     */
    private function _create_class_file($filename)
    {
        if (isset($this->namespace)) {
            $namespaceDeclaration = "\n\nnamespace $this->namespace;\n";
        } else {
            $namespaceDeclaration = "";
        }

        foreach ($this->m_types as $classfile) {
            if ($classfile['type'] != 'enum') {
                continue;
            }

            $classname = str_replace(".", "_", $classfile['name']);
            $classname = $this->toCamelCase($classname);

            $classDef = $this->_create_class_definition($classfile['value']);
            if (!$classDef) {
                continue;
            }

            $string = 'class ' . $classname  . "\n{\n";
            $string .= $classDef;
            $string .= "}\n";

            file_put_contents(
                $this->dir . $classname . '.php',
                '<?php' . $namespaceDeclaration . "\n" . $string
            );
        }
    }

    /**
     * Creates the class definitions
     * @param Array $classfile
     * @param String $string
     */
    private function _create_class_definition($classfile)
    {
        $string = '';

        // order array by keys
        foreach ($classfile as $field) {
            $fields[$field[0]] = $field[1];
        }
        ksort($fields);

        // get longest key length
        $pad = max(array_map('strlen', array_keys($fields)));

        foreach ($fields as $key => $val) {
        // Because field names might be PHP keywords, we prefix them with PB_
            $string .= '    const PB_' . str_pad($key, $pad) . '  = ' . $val . ";\n";
        }

        return $string;
    }

    /**
     * Parses the message
     * @param String $string the proton file as string
     */
    private function _parse_message_type(&$string, $m_name, $path = '')
    {
        $myarray = array();
        $string = trim($string);
        if ($string == '') {
            return;
        }

        // TODO (daniel): I think the right behavior would be to perform two passes over the input.
        //   First, all message declarations should be parsed and declared, then the definitions should
        //   be filled in.
        // first add a dummy entry to allow recursive type references
        if ($path != '') {
            $this->m_types[] = array('name' => $path , 'type' => 'message', 'value' => array());
        }

        //var_dump($m_name);

        while (strlen($string) > 0) {
            $next = ($this->_next($string));
            if (strtolower($next) == 'message') {
                $string = trim(substr($string, strlen($next)));
                $name = $this->_next($string);

                $offset = $this->_get_begin_end($string, "{", "}");
                // now extract the content and call parse_message again
                $content = trim(substr($string, $offset['begin'] + 1, $offset['end'] - $offset['begin'] - 2));
                $this->_parse_message_type($content, $name, trim($path . '.' . $name, '.'));

                $string = '' . trim(substr($string, $offset['end']));
            } elseif (strtolower($next) == 'enum') {
                $string = trim(substr($string, strlen($next)));
                $name = $this->_next($string);
                $offset = $this->_get_begin_end($string, "{", "}");
                // now extract the content and call _parse_enum
                $content = trim(substr($string, $offset['begin'] + 1, $offset['end'] - $offset['begin'] - 2));
                // now adding all to myarray
                $this->m_types[] = array('name' => trim($path . '.' . $name, '.'),
                                             'type' => 'enum', 'value' => $this->_parse_enum($content));
                // removing it from string
                $string = '' . trim(substr($string, $offset['end']));
            } elseif (strtolower($next) == 'extensions') {
                echo "WARNING: 'extensions' not supported. Ignoring.\n";
                $string = trim(substr($string, strlen($next)));

                $string = '' . trim(substr($string, strpos($string, ';') + 1));
            } else {
                // now a normal field
                $match = preg_match('/(.*);\s?/', $string, $matches, PREG_OFFSET_CAPTURE);
                if (!$match) {
                    throw new Exception('Proto file missformed');
                }
                $myarray[] = array('type' => 'field', 'value' => $this->_parse_field($matches[0][0], $myarray, $path));
                $string = trim(substr($string, $matches[0][1] + strlen($matches[0][0])));
            }
        }

        // now actually set the value field of the type to myarray
        foreach ($this->m_types as &$message) {
            if ($message['name'] == $path) {
                $message['value'] = $myarray;
                break;
            }
            unset($message);
        }
    }

    /**
     * Parses a normal field
     * @param String $content - content
     */
    private function _parse_field($content, $array, $path)
    {
        $myarray = array();

        // parse the default value
        $match = preg_match('/\[\s?default\s?=\s?([^\[]*)\]\s?;/', $content, $matches, PREG_OFFSET_CAPTURE);
        if ($match) {
            $myarray['default'] = $matches[1][0];
            $content = trim(substr($content, 0, $matches[0][1])) . ';';
        }

        // parse the value
        $match = preg_match('/=\s(.*);/', $content, $matches, PREG_OFFSET_CAPTURE);
        if ($match) {
            $myarray['value'] = trim($matches[1][0]);
            $content = trim(substr($content, 0, $matches[0][1]));
        } else {
            throw new Exception('Protofile no value at ' . $content);
        }

        // parse all modifier
        $content = trim(trim(trim($content), ';'));
        $typeset = false;
        while (strlen($content) > 0) {
            $matches = $this->_next($content, true);
            $name = $matches[0][0];
            if (strtolower($name) == 'optional') {
                $myarray['optional'] = true;
            } elseif (strtolower($name) == 'required') {
                $myarray['required'] = true;
            } elseif (strtolower($name) == 'repeated') {
                $myarray['repeated'] = true;
            } elseif ($typeset == false) {
                        $type = $this->_check_type($name, $array, $path);
                $myarray['type'] = $type[0];
                $myarray['namespace'] = $type[1];
                $typeset = true;
            } else {
                $myarray['name'] = $name;
            }
            $content = trim(substr($content, strlen($name)));
        }

        return $myarray;
    }


    /**
     * Checks if a type exists
     * @param String $type - the type
     */
    private function _check_type($type, $array, $path)
    {
        if (isset($this->scalar_types[strtolower($type)])) {
            return array(strtolower($type), '');
        }

        // absolute or relative thing
        // calculate namespace
        $namespace = $type;

        $apath = explode(".", $path);
        if ($apath > 1) {
            array_pop($apath);
            $namespace = trim(trim(join($apath, '.'), '.') . '.' . $type, '.');
        }

        // try the namespace
        foreach ($this->m_types as $message) {
            if ($message['name'] == $namespace) {
                return array($type, $namespace);
            }
        }

        // now try one deeper
        $namespace  = trim($path . '.' . $namespace, '.');
        foreach ($this->m_types as $message) {
            if ($message['name'] == $namespace) {
                return array($type, $namespace);
            }
        }

        // TODO (daniel): We should also try all higher namespaces I guess.
        //   This is really just a hack to make the RDB protocol file work.
        // try the top level
        // try the namespace
        foreach ($this->m_types as $message) {
            if ($message['name'] == $type) {
                return array($type, $type);
            }
        }

        // @TODO TYPE CHECK
        throw new Exception('Protofile type ' . $type . ' unknown!');
    }

    /**
     * Parses enum
     * @param String $content content of the enum
     */
    private function _parse_enum($content)
    {
        $myarray = array();
        $match = preg_match_all('/(.*);\s?/', $content, $matches);
        if (!$match) {
            throw new Execption('Semantic error in Enum!');
        }
        foreach ($matches[1] as $match) {
            $split = explode("=", $match);
            $myarray[] = array(trim($split[0]), trim($split[1]));
        }
        return $myarray;
    }

    /**
     * Gets the next String
     */
    private function _next($string, $reg = false)
    {
        $match = preg_match('/([^\s^\{}]*)/', $string, $matches, PREG_OFFSET_CAPTURE);
        if (!$match) {
            return -1;
        }
        if (!$reg) {
            return (trim($matches[0][0]));
        } else {
            return $matches;
        }
    }

    /**
     * Returns the begin and endpos of the char
     * @param String $string protofile as string
     * @param String $char begin element such as '{'
     * @param String $charend end element such as '}'
     * @return array begin, end
     */
    private function _get_begin_end($string, $char, $charend)
    {
        $offset_begin = strpos($string, $char);

        if ($offset_begin === false) {
            return array('begin' => -1, 'end' => -1);
        }

        $_offset_number = 1;
        $_offset = $offset_begin + 1;
        while ($_offset_number > 0 && $_offset > 0) {
        // now search after the end nested { }
            $offset_open = strpos($string, $char, $_offset);
            $offset_close = strpos($string, $charend, $_offset);
            if ($offset_open < $offset_close && !($offset_open === false)) {
                $_offset = $offset_open+1;
                $_offset_number++;
            } elseif (!($offset_close === false)) {
                $_offset = $offset_close+1;
                $_offset_number--;
            } else {
                $_offset = -1;
            }
        }

        if ($_offset == -1) {
            throw new Exception('Protofile failure: ' . $char . ' not nested');
        }

        return array('begin' => $offset_begin, 'end' => $_offset);
    }

    /**
     * Strips the comments out
     * @param String $string the proton file as string
     */
    private function _strip_comments(&$string)
    {
        $string = preg_replace('/\/\/.*/', '', $string);
        // now replace empty lines and whitespaces in front
        $string = preg_replace('/\\r?\\n\s*/', "\n", $string);
    }
}
