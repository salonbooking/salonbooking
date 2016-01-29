<?php

class SLN_Admin_Tools
{
    const PAGE = 'salon-tools';

    protected $plugin;
    protected $settings;
    public $settings_page = '';
    public function __construct(SLN_Plugin $plugin)
    {
		if (isset($_POST) && $_POST) 
			$this->save_settings ( $_POST);
		
        $this->plugin   = $plugin;
        $this->settings = $plugin->getSettings();
        add_action('admin_menu', array($this, 'admin_menu') );
    }

    public function admin_menu()
    {
        $this->settings_page = add_submenu_page(
            'salon',
            __('Salon Tools', 'salon-booking-system'),
            __('Tools', 'salon-booking-system'),
            apply_filters('salonviews/settings/capability', 'manage_salon'),
            self::PAGE,
            array($this, 'show')
        );
    }

    public function show()
    {
        echo $this->plugin->loadView(
            'admin/tools',
            array(
                'info' => var_export(get_option(SLN_Settings::KEY), true)
            )
        );
    }
	
	public function save_settings($data) {
		if (!isset($data['sln-tools-import']))
			return;
		
		//var_dump($data['tools-import']);
//		var_dump($this->print_r_reverse_multiple($data['tools-import']));
		eval('$somevar = ' . $data['tools-import']. ';');
		var_dump($somevar);
		var_dump(get_option(SLN_Settings::KEY));
		die();
	}
	
    function text_to_array($str) {

        //Initialize arrays
        $keys = array();
        $values = array();
        $output = array();

        //Is it an array?
        if( substr($str, 0, 5) == 'Array' ) {

            //Let's parse it (hopefully it won't clash)
            $array_contents = substr($str, 7, -2);
            $array_contents = str_replace(array('[', ']', '=>'), array('#!#', '#?#', ''), $array_contents);
            $array_fields = explode("#!#", $array_contents);

            //For each array-field, we need to explode on the delimiters I've set and make it look funny.
            for($i = 0; $i < count($array_fields); $i++ ) {

                //First run is glitched, so let's pass on that one.
                if( $i != 0 ) {

                    $bits = explode('#?#', $array_fields[$i]);
					var_dump($bits);
                    if( $bits[0] != '' ) $output[trim($bits[0])] = trim($bits[1]);

                }
            }

            //Return the output.
            return $output;

        } else {

            //Duh, not an array.
            echo 'The given parameter is not an array.';
            return null;
        }

    }
	
	function print_r_reverse(&$output)
{
    $expecting = 0; // 0=nothing in particular, 1=array open paren '(', 2=array element or close paren ')'
    $lines = explode("\n", $output);
    $result = null;
    $topArray = null;
    $arrayStack = array();
    $matches = null;
    while (!empty($lines) && $result === null)
    {
        $line = array_shift($lines);
        $trim = trim($line);
        if ($trim == 'Array')
        {
            if ($expecting == 0)
            {
                $topArray = array();
                $expecting = 1;
            }
            else
            {
                trigger_error("Unknown array.");
            }
        }
        else if ($expecting == 1 && $trim == '(')
        {
            $expecting = 2;
        }
        else if ($expecting == 2 && preg_match('/^\[(.+?)\] \=\> (.+)$/', $trim, $matches) !== false ) // array element
        {
            list ($fullMatch, $key, $element) = $matches;
            if (trim($element) == 'Array')
            {
                $topArray[$key] = array();
                $newTopArray =& $topArray[$key];
                $arrayStack[] =& $topArray;
                $topArray =& $newTopArray;
                $expecting = 1;
            }
            else
            {
                $topArray[$key] = $element;
            }
        }
        else if ($expecting == 2 && $trim == ')') // end current array
        {
            if (empty($arrayStack))
            {
                $result = $topArray;
            }
            else // pop into parent array
            {
                // safe array pop
                $keys = array_keys($arrayStack);
                $lastKey = array_pop($keys);
                $temp =& $arrayStack[$lastKey];
                unset($arrayStack[$lastKey]);
                $topArray =& $temp;
            }
        }
        // Added this to allow for multi line strings.
    else if (!empty($trim) && $expecting == 2)
    {
        // Expecting close parent or element, but got just a string
        $topArray[$key] .= "\n".$line;
    }
        else if (!empty($trim))
        {
            $result = $line;
        }
    }

    $output = implode("\n", $lines);
    return $result;
}

/**
* @param string $output : The output of a multiple print_r calls, separated by newlines
* @return mixed[] : parseable elements of $output
*/
function print_r_reverse_multiple($output)
{
    $result = array();
    while (($reverse = $this->print_r_reverse($output)) !== NULL)
    {
        $result[] = $reverse;
    }
    return $result;
}

}
