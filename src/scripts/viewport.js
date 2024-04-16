/**
 * Represents a utility class for managing viewport dimensions.
 * @class
 */
export default class ViewportDimensions {
    /**
     * Updates the viewport dimensions, accounting for browser UI elements.
     * @returns {Object} An object containing `width` and `height` properties.
     */
    static updateViewportDimensions() {
        const windowWidth = window.innerWidth;
        const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
        return { width: windowWidth, height: viewportHeight };
    }

    /**
     * Initializes the window resize event listener with a callback.
     * @param {Function} callback - The callback function to execute on resize.
     */
    static listenWindowResize(callback) {
        window.addEventListener('resize', () => {
            if (ViewportDimensions.resizeTimeout) {
                clearTimeout(ViewportDimensions.resizeTimeout);
            }

            ViewportDimensions.resizeTimeout = setTimeout(() => {
                const { width, height } = ViewportDimensions.updateViewportDimensions();
                if (callback && typeof callback === 'function') {
                    callback(width, height);
                }
            }, 50);
        });
    }

    /**
     * Retrieves the current width of the viewport.
     * @returns {number} The current width of the viewport.
     */
    static getViewportWidth() {
        return window.innerWidth;
    }

    /**
     * Retrieves the current height of the viewport, accounting for browser UI.
     * @returns {number} The current height of the viewport.
     */
    static getViewportHeight() {
        return window.innerHeight || document.documentElement.clientHeight;
    }
}
