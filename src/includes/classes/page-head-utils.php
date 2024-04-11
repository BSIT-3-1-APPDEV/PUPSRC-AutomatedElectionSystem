<?php

/**
 * Html Head tags utilities.
 *
 */
class PageHeadUtils
{
    private $title;
    private $is_change_base_url;
    private $base_url;
    private $description;
    private $url;
    private $theme;

    public function __construct($title, $description, $is_change_base_url = false)
    {
        $this->title = $title;
        $this->description = $description;
        $this->is_change_base_url = $is_change_base_url;
        $this->setBaseURL($this->is_change_base_url);
        $this->setUrl();
        $this->theme = "";
    }

    private function setBaseURL($set)
    {
        if ($set) {
            $current_dir = $_SERVER['SCRIPT_NAME'];
            $position = strpos($current_dir, "src");
            if ($position !== false) {
                $base_url = substr($current_dir, 0, $position);
                $base_url = ($base_url == '/' || $base_url == '\\') ? '/' : $base_url;
                $base_url = rtrim($base_url, '/\\');
                $this->base_url = $base_url;
            }
        }
    }

    private function setUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $this->url =  $protocol . $host . $uri;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getBaseURL()
    {
        if ($this->is_change_base_url) {
            return $this->base_url;
        }
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getTheme()
    {
        return $this->theme;
    }
}
