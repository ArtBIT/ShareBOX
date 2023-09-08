<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class AssetManager is used to keep track of all the assets.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Djordje Ungar 2014
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */
class AssetGroup
{
    protected $_registered_files = array();
    protected $_aliases = array();
    protected $_paths = array();
    protected $_groups = array();
    protected $_name = '';
    protected $_debug = true;

    protected $_default_options = array(
        'name' => 'assets',
        'paths' => array(),
        'aliases' => array()
    );

    public function __construct($options = array())
    {
        $options = array_merge($this->_default_options, $options);
        $this->_name = isset($options['name']) ? $options['name'] : 'default';
        $this->_paths = isset($options['paths']) ? $options['paths'] : array();
        $this->_paths[] = '';
        if ($aliases = (isset($options['aliases']) ? $options['aliases'] : array())) {
            foreach ($aliases as $short => $full) {
                $this->define_alias($short, $full);
            }
        }
        $this->cachebuster = CACHE_VERSION;
    }

    public function add_file()
    {
        $args = func_get_args(/* $group, $subgroup, $subsubgroup ... $filename*/);
        $filename = array_pop($args);
        if ($this->is_registered($filename)) {
            //log_message("File $filename already included!");
            return;
        }

        $groups = explode('.', implode('.', $args));
        if (count($groups) == 1 && empty($groups[0])) {
            $groups = array();
        }
        $this->register_file($filename, implode('.', $groups));
        $current_group =& $this->_groups;

        $filepath = $this->expand_path($this->expand_alias($filename));
        foreach ($groups as $group) {
            if (!isset($current_group[$group])) {
                $current_group[$group] = array();
            }
            $current_group =& $current_group[$group];
        }
        if ($this->cachebuster) {
            if (strpos($filepath, '?') !== false) {
                $filepath .= '&' . $this->cachebuster;
            } else {
                $filepath .= '?' . $this->cachebuster;
            }
        }
        $current_group[] = $filepath;
    }

    public function is_registered($filename)
    {
        return isset($this->_registered_files[$filename]);
    }

    public function register_file($filename, $value = true)
    {
        $this->_registered_files[$filename] = $value;
    }

    protected function debug_print($message, $obj)
    {
        if ($this->_debug) {
            echo $message;
            print_r($obj);
        }
    }
    public function define_alias($shortcut, $path)
    {
        $this->_aliases[$shortcut] = rtrim($path, '/');
    }

    protected function expand_alias($filename)
    {
        foreach ($this->_aliases as $symbolic => $full) {
            $len = strlen($symbolic);
            if (substr($filename, 0, $len) == $symbolic) {
                $filename = "{$full}/" . substr($filename, $len);
                return $filename;
            }
        }
        return $filename;
    }

    protected function expand_path($filename)
    {
        if (strpos($filename, 'http:') === false) {
            $filename = "/" . trim($filename, "/");
            $root = rtrim(FCPATH, '/');
            foreach ($this->_paths as $path) {
                $path = trim($path, "/");
                if (strlen($path)) {
                    $path = "/" . $path;
                }
                $filepath = $path . $filename;
                if (file_exists($root . $filepath)) {
                    return base_url() . ltrim($filepath, "/");
                }
            }
        }
        return $filename;
    }

    public function get_assets()
    {
        $groups = explode('.', implode('.', func_get_args()));
        if (count($groups) == 1 && empty($groups[0])) {
            $groups = array();
        }
        $current_group =& $this->_groups;
        foreach ($groups as $group) {
            if (!isset($current_group[$group])) {
                return array();
            }
            $current_group =& $current_group[$group];
        }
        return $this->_get_all_children($current_group);
    }

    protected function _get_all_children($group)
    {
        $suffix = '';
        if (ENVIRONMENT == 'development') {
            //$suffix = '?'.time();
        }
        $result = array();
        foreach ($group as $item) {
            if (is_array($item)) {
                $result = array_merge($result, $this->_get_all_children($item));
            } else {
                $result[] = $item . $suffix;
            }
        }
        return $result;
    }
}

class Assets
{
    protected $_groups = array();
    protected $_default_options = array(
        'name' => 'assets',
        'paths' => array(),
        'aliases' => array()
    );
    protected $_inline_data = array();

    public function __construct($options = array())
    {
        $options = array_merge($this->_default_options, $options);
        $this->_groups = array(
            DOCUMENT_HEAD_START => new AssetGroup(array_merge(array('name'=>'head_start'), $options)),
            DOCUMENT_HEAD       => new AssetGroup(array_merge(array('name'=>'head'), $options)),
            DOCUMENT_BODY_START => new AssetGroup(array_merge(array('name'=>'body_start'), $options)),
            DOCUMENT_BODY_END   => new AssetGroup(array_merge(array('name'=>'body_end'), $options)),
        );
        $this->_inline_data = array(
            DOCUMENT_HEAD_START => array(),
            DOCUMENT_HEAD       => array(),
            DOCUMENT_BODY_START => array(),
            DOCUMENT_BODY_END   => array(),
        );
    }

    public function is_file_included($filename)
    {
        foreach ($this->_groups as $manager) {
            if ($manager->is_registered($filename)) {
                return true;
            }
        }
        return false;
    }


    public function add_assets($document_section, $group, $filename)
    {
        if (is_array($filename)) {
            foreach ($filename as $item) {
                $this->add_assets($document_section, $group, $item);
            }
        } else {
            if (isset($this->_groups[$document_section])) {
                $this->_groups[$document_section]->add_file($group, $filename);
            }
        }
    }
    public function add_js_file($filename,   $document_section = DOCUMENT_BODY_END)
    {
        $this->add_assets($document_section, 'js', $filename);
    }
    public function add_js_files($filename,  $document_section = DOCUMENT_BODY_END)
    {
        $this->add_assets($document_section, 'js', $filename);
    }
    public function add_css_file($filename,  $document_section = DOCUMENT_HEAD)
    {
        $this->add_assets($document_section, 'css', $filename);
    }
    public function add_css_files($filename, $document_section = DOCUMENT_HEAD)
    {
        $this->add_assets($document_section, 'css', $filename);
    }

    public function render_group($document_section)
    {
        $tags = array();
        if (isset($this->_groups[$document_section])) {
            $manager = $this->_groups[$document_section];
            $files = $manager->get_assets('js');
            foreach ($files as $filename) {
                $tags[] = $this->render_html_tag('script', array('src'=>$filename, 'type'=>'text/javascript'), true);
            }
            $files = $manager->get_assets('css');
            foreach ($files as $filename) {
                $tags[] = $this->render_html_tag('link', array('href'=>$filename, 'type'=>'text/css', 'rel'=>'stylesheet', 'media'=>'Screen,Projection,TV'), true);
            }
        }
        if (isset($this->_inline_data[$document_section])) {
            $inline_data = $this->_inline_data[$document_section];
            foreach ($inline_data as $data) {
                $tags[] = $this->render_html_tag($data['tag'], $data['attributes'], true);
            }
        }
        return implode("\n", $tags);
    }

    public function add_css_data($data, $document_section = DOCUMENT_HEAD)
    {
        $this->_inline_data[$document_section][] = array('tag' => 'style', 'attributes' => array('type'=>'text/css', 'content'=>$data));
    }
    public function add_js_data($data, $document_section = DOCUMENT_BODY_END)
    {
        $this->_inline_data[$document_section][] = array('tag' => 'script', 'attributes' => array('type'=>'text/javascript', 'content'=>$data));
    }

    protected function render_html_tag($tag_name, $tag_attributes, $capture_output = false)
    {
        $auto_close_tags = array('link', 'meta');
        ob_start();

        echo "<$tag_name";
        foreach ($tag_attributes as $tag_attribute_name => $tag_attribute_value) {
            if (!(($tag_name == 'script' || $tag_name == 'style')
                && $tag_attribute_name == 'content')) {
                echo " $tag_attribute_name='$tag_attribute_value'";
            }
        }
        if (in_array($tag_name, $auto_close_tags)) {
            echo " />\n";
        } else {
            echo ">";
            // if the tag is either a script or a style tag
            // and if they have a 'content' attribute - output it
            if (($tag_name == 'script' || $tag_name == 'style')
                && isset($tag_attributes['content'])) {
                // inline javascript
                if ($tag_name == 'script') {
                    echo "{$tag_attributes['content']}\n";
                } else {
                    echo $tag_attributes['content'];
                }
            }
            echo "</$tag_name>\n";
        }

        $output = ob_get_contents();
        ob_end_clean();
        if ($capture_output) {
            return $output;
        }
        echo $output;
    }
}
