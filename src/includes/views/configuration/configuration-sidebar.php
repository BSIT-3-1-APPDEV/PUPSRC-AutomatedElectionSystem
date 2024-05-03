<!-- Modify Sidebar relative paths affected by routing page requests -->
<?php
require_once FileUtils::normalizeFilePath('includes/error-reporting.php');
// Capture the output of including the sidebar file
ob_start();
include_once FileUtils::normalizeFilePath(Path::COMPONENTS_PATH . '/sidebar.php');
$sidebar_content = ob_get_clean();

$temporary_html = '<!DOCTYPE html>
                   <html lang="en">
                   <head>
                       <meta charset="UTF-8">
                       <title>Temporary Sidebar Content</title>
                   </head>
                   <body>' . $sidebar_content . '</body>
                   </html>';


$src_prefix = 'src/'; // Prefix to prepend to img src attributes

// Use DOMDocument to manipulate the HTML
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($temporary_html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
libxml_clear_errors();

// Find all img elements in the sidebar content
$images = $dom->getElementsByTagName('img');
foreach ($images as $img) {
    // Get the current src attribute value
    $current_src = $img->getAttribute('src');

    // Prepend the prefix to the current src attribute value
    $new_src = $src_prefix . $current_src;

    // Update the src attribute of the img element
    $img->setAttribute('src', $new_src);
}

// Assuming $dom is your DOMDocument instance
$nav_links = $dom->getElementsByTagName('li'); // Get all <li> elements

if ($nav_links->length > 0) {
    foreach ($nav_links as $li) {
        $anchor = $li->getElementsByTagName('a')->item(0); // Get the first <a> tag within the <li>

        if ($anchor) {
            // Get the current href attribute value
            $current_href = $anchor->getAttribute('href');

            // Check if current href contains '#'
            if (strpos($current_href, '#') !== false) {
                continue; // Skip if href contains '#'
            }
            if (strpos($current_href, 'src') !== false) {
                continue; // Skip if href contains 'src'
            }

            // Prepend the prefix to the current href attribute value
            $new_href = $src_prefix . $current_href;

            // Update the href attribute of the <a> tag
            $anchor->setAttribute('href', $new_href);
        }
    }
}


// Get the updated HTML content
$updated_sidebar_content = '';
foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $node) {
    $updated_sidebar_content .= $dom->saveHTML($node);
}


// Output the modified sidebar content
// Output the modified sidebar content (extracted from wrapped HTML)
echo $updated_sidebar_content;

?>