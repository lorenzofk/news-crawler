/**
 * Escapes HTML entities to prevent XSS attacks.
 * 
 * @param {string} text The text to escape.
 * 
 * @returns {string} The escaped text safe for HTML insertion.
 */
export function escapeHtml(text) {
    if (text === null) return '';
    
    const div = document.createElement('div');
    
    div.textContent = text;
    
    return div.innerHTML;
}

/**
 * Escapes and validates URL to prevent XSS in href attributes.
 * 
 * @param {string} url The URL to escape and validate.
 * 
 * @returns {string} The validated URL or '#' if invalid.
 */
export function escapeUrl(url) {
    if (! url) {
        return '#';
    }
    
    if (!/^https?:\/\//i.test(url)) {
        return '#';
    }
    
    return url;
}

