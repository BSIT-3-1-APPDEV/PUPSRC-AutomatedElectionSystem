
export function initializeConfigurationJS() {
    let tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    let tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    console.log(tooltipList);
}

export function shortFnv1a(input) {
    let hash = 2166136261; // FNV offset basis
    for (let i = 0; i < input.length; i++) {
        hash ^= input.charCodeAt(i);
        hash *= 16777619; // FNV prime
    }
    const hexString = (hash >>> 0).toString(16); // Convert to unsigned 32-bit integer and then to hexadecimal string
    return ('0000' + hexString).slice(-4); // Ensure output is 4 characters long
}
