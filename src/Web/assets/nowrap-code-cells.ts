document.querySelectorAll('td').forEach(td => {
    // Check if the td contains only a code element (no text content outside code)
    const children = Array.from(td.childNodes);

    // Filter out empty text nodes (whitespace only)
    const significantNodes = children.filter(node => {
        if (node.nodeType === Node.TEXT_NODE) {
            return node.textContent?.trim() !== '';
        }
        return true;
    });

    // Check if only one significant node exists and it's a code element
    if (significantNodes.length === 1 &&
        significantNodes[0].nodeType === Node.ELEMENT_NODE &&
        (significantNodes[0] as Element).tagName === 'CODE') {
        td.classList.add('nowrap');
    }
});
