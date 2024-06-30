
export function initializeConfigurationJS(ConfigPage = null) {

    let tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    let tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    const toastElList = document.querySelectorAll('.toast');
    const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl));
    // toastList.forEach(toast => toast.show());

    try {
        SortableTiles[0].destroy()
    } catch (error) {
        console.warn(error);
    }
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

export class EventListenerUtils {
    /**
     * Removes all event listeners stored in the provided Map.
     * It iterates over the Map and removes each event listener using removeEventListener(),
     * and then clears the Map.
     * @function
     * @name removeEventListeners
     * @param {Map} eventListenersMap - The Map containing event listeners to be removed.
     */

    static clearEventListeners(eventListenersMap) {
        if (eventListenersMap && eventListenersMap instanceof Map && eventListenersMap.size > 0) {
            eventListenersMap.forEach((listener, element) => {
                element.removeEventListener(listener.event, listener.handler);
            });

            eventListenersMap.clear();
        }
    }

    /**
     * Adds an event listener to the specified element and stores it in the provided Map.
     * @function
     * @name addEventListenerAndStore
     * @param {Element} element - The DOM element to which the event listener is added.
     * @param {string} event - The name of the event to listen for.
     * @param {function} handler - The function to be executed when the event is triggered.
     * @param {Map} eventListenersMap - The Map in which the event listener will be stored.
     */
    static addEventListenerAndStore(element, event, handler, eventListenersMap) {
        element.addEventListener(event, handler);
        const key = `${element}-${event}`;
        eventListenersMap.set(key, handler);
    }



    /**
     * Removes the event listener associated with the specified element and deletes its entry from the provided Map.
     * @function
     * @name removeEventListenerAndDelete
     * @param {Element} element - The DOM element from which the event listener is removed.
     * @param {string} event - The name of the event for which the listener is to be removed.
     * @param {Map} eventListenersMap - The Map from which the event listener will be removed.
     */
    static removeEventListenerAndDelete(element, event, eventListenersMap) {
        const key = `${element}-${event}`;
        if (eventListenersMap.has(key)) {
            const listener = eventListenersMap.get(key);
            element.removeEventListener(listener.event, listener.handler);
            eventListenersMap.delete(key);
        }
    }

}
