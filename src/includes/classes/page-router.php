<?php

/**
 * Class PageRouter
 *
 * This class handles routing and includes PHP files based on the requested URI.
 *
 * Usage:
 * 1. Create an instance of PageRouter by passing an array of sub_pages.
 * 2. Call handleRequest() to process the incoming request.
 */
class PageRouter
{
    /** @var array List of sub pages */
    private $sub_pages;
    /** @var array Associative array mapping page URIs to file paths */
    private $Pages;

    /**
     * PageRouter constructor.
     *
     * Initializes the PageRouter with the given sub_pages array.
     * The first value in the sub_pages array will be set as the default page.
     *
     * @param array $sub_pages An array of sub pages used for routing request.
     */
    public function __construct(array $sub_pages)
    {
        $this->sub_pages = $sub_pages;
        $this->initializePages();
    }

    private function initializePages()
    {
        // Sets the default page
        $this->Pages = [
            '' => "'" . $this->sub_pages[0] . ".php'",
        ];

        foreach ($this->sub_pages as $sub_page) {
            $this->Pages[$sub_page] = "/$sub_page.php";
        }
    }

    /**
     * Handles the incoming HTTP request.
     * Determines the requested page and includes the corresponding PHP file.
     * If the requested page is not found, the default page is included.
     * The default page is the first string passed in the constructor
     */
    public function handleRequest()
    {
        $PageUri = basename($_SERVER['PATH_INFO']);

        if (isset($this->Pages[$PageUri])) {
            require_once(Path::CONFIGURATION_VIEWS . $this->Pages[$PageUri]);
        } else {
            http_response_code(404);
            require_once(Path::CONFIGURATION_VIEWS . $this->Pages[$this->sub_pages[0]]);
        }
    }
}
