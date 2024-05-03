<!-- Modify Sidebar relative paths affected by routing page requests -->
<?php
require_once FileUtils::normalizeFilePath('includes/error-reporting.php');
// Capture the output of including the sidebar file
ob_start();
include_once FileUtils::normalizeFilePath(Path::COMPONENTS_PATH . '/footer.php');
$footer_content = ob_get_clean();

$temporary_html = '<!DOCTYPE html>
                   <html lang="en">
                   <head>
                       <meta charset="UTF-8">
                       <title>Temporary Sidebar Content</title>
                   </head>
                   <body>' . $footer_content . '</body>
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


// Get the updated HTML content
$updated_footer_content = '';
foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $node) {
    $updated_footer_content .= $dom->saveHTML($node);
}


// Output the modified sidebar content
// Output the modified sidebar content (extracted from wrapped HTML)
echo $updated_footer_content;

?>