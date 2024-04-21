<?php
class SecondaryNav
{
    private $base_page;
    private $pages;

    public function __construct($pages, $link_names, $is_routed = false)
    {
        $this->initializePages($pages, $link_names);
        if ($is_routed) {
            $this->base_page = $_SERVER['SCRIPT_NAME'];
        } else {
            $this->base_page = "/";
        }
    }

    private function initializePages($pages, $link_names)
    {


        for ($i = 0; $i < count($pages); $i++) {
            $this->pages[$pages[$i]] = "$link_names[$i]";
        }
    }

    public function getNavLink()
    {
        $page_uri = '';
        if (isset($_SERVER['PATH_INFO'])) {
            $page_uri = basename($_SERVER['PATH_INFO']);
        }

        $prev = $active = '';

        $navLinks = '<div class="">
                        <ul class="nav" id="" role="tablist">';

        foreach ($this->pages as $key => $page) {
            if ($page_uri === '') {
                $page_uri = $key;
            }
            if ($page_uri === $key) {
                $active = ' active ';
            } else {
                $active = '';
            }


            $navLinks .= "
                            <li class=\"nav-item secondary-nav\" role=\"navigation\">
                                <a class=\"nav-link $active secondary-nav px-0 pb-0 ml-4 mb-10\" id=\"\" data-toggle=\"tab\" href=\"$this->base_page/$key\" data-target=\"\" role=\"tab\" aria-controls=\"\" aria-selected=\"false\">$page</a>
                            </li>
        ";
        }


        $navLinks .= '  </ul>
                    </div>';

        echo $navLinks;
    }
}
