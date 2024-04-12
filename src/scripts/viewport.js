/**
 * Represents a utility class for managing viewport dimensions.
 * @class
 */
export default class ViewportDimensions {
    /**
     * Creates an instance of ViewportDimensions.
     * Initializes the viewport dimensions and sets up the resize event handler.
     */
    constructor(callback) {
        /**
         * @member {number} viewWidth - The current width of the viewport.
         */
        this.viewWidth = 0;

        /**
         * @member {number} viewHeight - The current height of the viewport, accounting for browser UI.
         */
        this.viewHeight = 0;

        // Initialize the viewport dimensions
        this.updateViewportDimensions();

        if (typeof callback === 'function') {
            callback();
        }

    }

    /**
     * Handles the window resize event by updating the viewport dimensions.
     */
    listenWindowResize(callback) {
        $(window).resize(() => {
            clearTimeout(window.resizedFinished);
            window.resizedFinished = setTimeout(() => {
                this.updateViewportDimensions();
                if (typeof callback === 'function') {
                    callback();
                }
            }, 50);
        });
    }




    /**
     * Updates the viewport dimensions, accounting for browser UI elements.
     */
    updateViewportDimensions() {
        // Get the current window dimensions
        const windowWidth = $(window).width();

        // Calculate the viewport height accounting for browser UI
        const viewportHeight = window.innerHeight || document.documentElement.clientHeight;

        // Update the viewport dimensions
        this.viewWidth = windowWidth;
        this.viewHeight = viewportHeight;
        console.log(`Width ${this.viewWidth} Height  ${this.viewHeight}`);
    }

    /**
     * Retrieves the current width of the viewport.
     * @returns {number} The current width of the viewport.
     */
    getViewportWidth() {
        return this.viewWidth;
    }

    /**
     * Retrieves the current height of the viewport, accounting for browser UI.
     * @returns {number} The current height of the viewport.
     */
    getViewportHeight() {
        return this.viewHeight;
    }
}
