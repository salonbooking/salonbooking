<?php

class SLN_Service_Templating
{
    private $plugin;
    private $paths;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function addPath($path, $priority)
    {
        while (isset($this->paths[$priority])) {
            $priority++;
        }
        $this->paths[$priority] = $path;
    }

    public function loadView($view, $data = array())
    {
        ob_start();
        extract($data);
        $plugin = $this->plugin;
        include $this->getViewFile($view);

        return ob_get_clean();
    }

    public function getViewFile($view)
    {
        $file = $this->getViewFileName($view);
        $file = apply_filters("sln.templating.getViewFile", $file, $view);
        if ( ! $file) {
            throw new SLN_Exception(sprintf('view "%s" not found ', $view));
        }

        return $file;
    }


    private function getViewFileName($view)
    {
        foreach ($this->paths as $path) {
            $file = sprintf($path, $view);
            if (file_exists($file)) {
                return $file;
            }
        }
    }
}