import ViewportDimensions from './viewport.js';

export default function setTextEditableWidth(input_element) {
    const input = input_element;
    const value = input.value;
    const placeholderText = input.getAttribute('placeholder');

    // Determine the text content to measure (value or placeholder)
    const textContent = value || placeholderText;

    // Create a temporary span element to measure text width
    const tempElement = document.createElement('span');
    tempElement.textContent = textContent;
    tempElement.style.visibility = 'hidden';
    tempElement.style.position = 'absolute';

    // Copy font styles from input element to the temporary element
    tempElement.style.fontSize = window.getComputedStyle(input).fontSize;
    tempElement.style.fontFamily = window.getComputedStyle(input).fontFamily;
    tempElement.style.fontWeight = window.getComputedStyle(input).fontWeight;
    tempElement.style.fontStyle = window.getComputedStyle(input).fontStyle;
    tempElement.style.letterSpacing = window.getComputedStyle(input).letterSpacing;
    tempElement.style.textTransform = window.getComputedStyle(input).textTransform;

    // Append the temporary element to the document body to measure its width
    document.body.appendChild(tempElement);

    // Get the measured width of the text content
    const textWidth = tempElement.offsetWidth + (ViewportDimensions.getViewportWidth() * 0.005);

    // Set the width of the input element based on the text width
    input.style.width = textWidth + 'px';

    // Remove the temporary element from the document body
    document.body.removeChild(tempElement);
}