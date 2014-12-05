<?php

class SLN_Shortcode_Saloon
{
    private $plugin;
    private $attrs;

    function __construct(SLN_Plugin $plugin, $attrs)
    {
        $this->plugin = $plugin;
        $this->attrs  = $attrs;
    }

    public static function init(SLN_Plugin $plugin)
    {
        add_shortcode(
            'saloon',
            function ($attrs) use ($plugin) {
                $obj = new self($plugin, $attrs);

                return $obj->execute();
            }
        );
    }


    public function execute()
    {
        ob_start();
        echo "saloon";
        $ret = ob_get_clean();

        return $ret;
    }
}