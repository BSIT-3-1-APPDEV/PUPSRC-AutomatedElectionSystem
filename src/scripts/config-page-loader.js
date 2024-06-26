// Function to perform fetch request and update content
function fetchAndReplaceContent(url) {

    fetch(url)
        .then(response => response.text())
        .then(async (html) => {
            // Remove existing scripts in the body
            document.body.querySelectorAll('script').forEach(script => {
                script.parentNode.removeChild(script);
            });

            // Remove existing styles in the body
            document.body.querySelectorAll('link[rel="stylesheet"]').forEach(link => {
                link.parentNode.removeChild(link);
            });

            // Parse the fetched HTML
            var parser = new DOMParser();
            var newDocument = parser.parseFromString(html, 'text/html');

            // Get the content to replace
            var newMainContent = newDocument.querySelector('main');

            // Get existing main element
            var mainElement = document.querySelector('main');

            // Clear existing content
            mainElement.innerHTML = '';

            // Transfer the children of the fetched main content to the existing main element
            newMainContent.childNodes.forEach(child => {
                mainElement.appendChild(child.cloneNode(true));
            });

            // Add new scripts
            let scripts = newDocument.querySelectorAll('script');
            for (let script of scripts) {
                console.log(script);
                if (script.src) {
                    let srcWithoutPrefix = script.src.replace('src/', '');

                    let newScript = document.createElement('script');
                    newScript.src = srcWithoutPrefix;

                    try {
                        await loadScript(newScript.src, script.type, script.defer);
                        console.log('Script loaded successfully!');
                    } catch (error) {
                        console.error(`Error loading script: ${error}`);
                    }
                } else {
                    // Execute inline scripts
                    eval(script.textContent);
                }
            }



            // Add new styles
            newDocument.querySelectorAll('link[rel="stylesheet"]').forEach(link => {
                let hrefWithoutPrefix = link.href.replace('src/', '');

                let newLink = document.createElement('link');
                newLink.rel = 'stylesheet';
                newLink.href = hrefWithoutPrefix;
                document.head.appendChild(newLink);
            });
        })
        .catch(error => {
            console.error('Error fetching content:', error);
        });
}

function loadScript(src, type, isDefered) {
    return new Promise((resolve, reject) => {
        let script = document.createElement('script');
        script.type = type;
        script.src = src;
        script.defer = isDefered;
        script.onload = () => resolve(script);
        script.onerror = () => reject(new Error(`Script load error for ${src}`));
        document.body.append(script);
    });
}

// Add click event listener to links
document.querySelectorAll('.secondary-nav-container .nav-link').forEach(link => {
    link.addEventListener('click', function (event) {
        event.preventDefault();
        fetchAndReplaceContent('src/includes/views/configuration/page-server.php' + this.getAttribute('href'));
    });
});
