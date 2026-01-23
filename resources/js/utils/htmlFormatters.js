// Utility functions for HTML content formatting

/**
 * Format plain text with paragraphs
 * @param {string} text - Plain text
 * @returns {string} HTML with paragraph tags
 */
export const formatTextWithParagraphs = (text) => {
  if (!text) return '';
  const paragraphs = text.split('\n\n');
  return paragraphs
    .filter(p => p.trim())
    .map(p => `<p>${p.replace(/\n/g, '<br/>')}</p>`)
    .join('');
};

/**
 * Sanitize HTML content (basic version)
 * @param {string} html - HTML string
 * @returns {string} Sanitized HTML
 */
export const sanitizeHTML = (html) => {
  if (!html) return '';
  
  // Basic sanitization - allow common tags
  const allowedTags = [
    'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
    'p', 'br', 'span', 'div',
    'strong', 'b', 'em', 'i', 'u',
    'ul', 'ol', 'li',
    'a', 'img', 'blockquote', 'code', 'pre',
    'table', 'thead', 'tbody', 'tr', 'th', 'td',
    'hr'
  ];
  
  const allowedAttributes = {
    a: ['href', 'target', 'rel', 'title'],
    img: ['src', 'alt', 'title', 'width', 'height'],
    table: ['border', 'cellpadding', 'cellspacing'],
    td: ['colspan', 'rowspan'],
    th: ['colspan', 'rowspan']
  };

  // Create a temporary div to parse HTML
  const temp = document.createElement('div');
  temp.innerHTML = html;
  
  // Remove disallowed tags
  const walker = document.createTreeWalker(
    temp,
    NodeFilter.SHOW_ELEMENT,
    null,
    false
  );
  
  const nodesToRemove = [];
  let node;
  while (node = walker.nextNode()) {
    if (!allowedTags.includes(node.tagName.toLowerCase())) {
      nodesToRemove.push(node);
    } else {
      // Remove disallowed attributes
      const allowedAttrs = allowedAttributes[node.tagName.toLowerCase()] || [];
      Array.from(node.attributes).forEach(attr => {
        if (!allowedAttrs.includes(attr.name)) {
          node.removeAttribute(attr.name);
        }
      });
    }
  }
  
  nodesToRemove.forEach(node => node.parentNode?.removeChild(node));
  
  return temp.innerHTML;
};

/**
 * Extract headings from HTML for table of contents
 * @param {string} html - HTML content
 * @returns {Array} Array of heading objects
 */
export const extractHeadings = (html) => {
  if (!html) return [];
  
  const regex = /<h([2-4])[^>]*>(.*?)<\/h\1>/gi;
  const headings = [];
  let match;
  
  while ((match = regex.exec(html)) !== null) {
    const level = parseInt(match[1]);
    const text = match[2].replace(/<[^>]*>/g, '').trim();
    const id = text
      .toLowerCase()
      .replace(/[^\w\s-]/g, '')
      .replace(/\s+/g, '-');
    
    if (text) {
      headings.push({
        level,
        text,
        id: id || `heading-${headings.length}`,
        original: match[0]
      });
    }
  }
  
  return headings;
};

/**
 * Calculate reading time in minutes
 * @param {string} text - Text content
 * @returns {number} Reading time in minutes
 */
export const calculateReadingTime = (text) => {
  if (!text) return 0;
  
  // Remove HTML tags for accurate word count
  const cleanText = text.replace(/<[^>]*>/g, ' ');
  const wordCount = cleanText.trim().split(/\s+/).length;
  
  // Average reading speed: 200 words per minute
  return Math.max(1, Math.ceil(wordCount / 200));
};

/**
 * Add IDs to headings for anchor links
 * @param {string} html - HTML content
 * @returns {string} HTML with heading IDs
 */
export const addHeadingIds = (html) => {
  if (!html) return '';
  
  return html.replace(
    /<h([2-4])([^>]*)>(.*?)<\/h\1>/gi,
    (match, level, attrs, content) => {
      const text = content.replace(/<[^>]*>/g, '').trim();
      const id = text
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-');
      
      return `<h${level}${attrs} id="${id}">${content}</h${level}>`;
    }
  );
};

/**
 * Truncate HTML content with preserved formatting
 * @param {string} html - HTML content
 * @param {number} maxLength - Maximum length in characters
 * @returns {string} Truncated HTML
 */
export const truncateHTML = (html, maxLength = 500) => {
  if (!html || html.length <= maxLength) return html;
  
  let truncated = '';
  let length = 0;
  let inTag = false;
  
  for (let i = 0; i < html.length; i++) {
    const char = html[i];
    
    if (char === '<') {
      inTag = true;
    } else if (char === '>') {
      inTag = false;
    }
    
    truncated += char;
    
    if (!inTag && char !== '>' && char !== '<') {
      length++;
    }
    
    if (length >= maxLength && !inTag) {
      // Find the last space before maxLength
      let lastSpace = truncated.lastIndexOf(' ');
      if (lastSpace > maxLength * 0.8) {
        truncated = truncated.substring(0, lastSpace);
      }
      truncated += '...';
      break;
    }
  }
  
  // Close any open tags
  const tagStack = [];
  const tagRegex = /<\/?([a-z][a-z0-9]*)[^>]*>/gi;
  let tagMatch;
  
  while ((tagMatch = tagRegex.exec(truncated)) !== null) {
    const [fullTag, tagName] = tagMatch;
    if (fullTag.startsWith('</')) {
      // Closing tag
      const lastIndex = tagStack.lastIndexOf(tagName);
      if (lastIndex !== -1) {
        tagStack.splice(lastIndex, 1);
      }
    } else if (!fullTag.endsWith('/>')) {
      // Opening tag (not self-closing)
      tagStack.push(tagName);
    }
  }
  
  // Close tags in reverse order
  while (tagStack.length > 0) {
    const tag = tagStack.pop();
    truncated += `</${tag}>`;
  }
  
  return truncated;
};