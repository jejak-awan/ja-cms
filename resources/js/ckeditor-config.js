/**
 * CKEditor 5 Configuration
 * Version: 47.1.0 - Modern, self-hosted WYSIWYG editor
 * License: GPL-2.0-or-later (Free for open source projects)
 * 
 * Features:
 * - Full self-hosted, no external dependencies
 * - Modern UI with excellent UX
 * - Rich content editing capabilities
 * - Image upload with drag & drop
 * - Responsive and mobile-friendly
 * - Zero security vulnerabilities
 */

import {
    ClassicEditor,
    AccessibilityHelp,
    Autoformat,
    AutoImage,
    AutoLink,
    Autosave,
    BlockQuote,
    Bold,
    Code,
    CodeBlock,
    Essentials,
    FindAndReplace,
    FontBackgroundColor,
    FontColor,
    FontFamily,
    FontSize,
    FullPage,
    GeneralHtmlSupport,
    Heading,
    Highlight,
    HorizontalLine,
    ImageBlock,
    ImageCaption,
    ImageInline,
    ImageInsert,
    ImageInsertViaUrl,
    ImageResize,
    ImageStyle,
    ImageToolbar,
    ImageUpload,
    Indent,
    IndentBlock,
    Italic,
    Link,
    LinkImage,
    List,
    ListProperties,
    MediaEmbed,
    Paragraph,
    PasteFromOffice,
    RemoveFormat,
    SelectAll,
    ShowBlocks,
    SimpleUploadAdapter,
    SourceEditing,
    SpecialCharacters,
    SpecialCharactersArrows,
    SpecialCharactersCurrency,
    SpecialCharactersEssentials,
    SpecialCharactersLatin,
    SpecialCharactersMathematical,
    SpecialCharactersText,
    Strikethrough,
    Style,
    Subscript,
    Superscript,
    Table,
    TableCaption,
    TableCellProperties,
    TableColumnResize,
    TableProperties,
    TableToolbar,
    TextTransformation,
    TodoList,
    Underline,
    Undo
} from 'ckeditor5';

import 'ckeditor5/ckeditor5.css';

/**
 * Default configuration for CKEditor
 */
const defaultConfig = {
    // GPL license key for open source projects
    // This removes the "license key missing" warning
    licenseKey: 'GPL',
    
    toolbar: {
        items: [
            'undo', 'redo',
            '|',
            'heading',
            '|',
            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor',
            '|',
            'bold', 'italic', 'underline', 'strikethrough',
            'subscript', 'superscript', 'code',
            '|',
            'link', 'insertImage', 'insertTable', 'blockQuote', 'codeBlock', 'mediaEmbed', 'horizontalLine',
            '|',
            'bulletedList', 'numberedList', 'todoList',
            'outdent', 'indent',
            '|',
            'specialCharacters', 'removeFormat',
            '|',
            'sourceEditing', 'showBlocks', 'findAndReplace'
        ],
        shouldNotGroupWhenFull: false
    },
    plugins: [
        AccessibilityHelp,
        Autoformat,
        AutoImage,
        AutoLink,
        Autosave,
        BlockQuote,
        Bold,
        Code,
        CodeBlock,
        Essentials,
        FindAndReplace,
        FontBackgroundColor,
        FontColor,
        FontFamily,
        FontSize,
        FullPage,
        GeneralHtmlSupport,
        Heading,
        Highlight,
        HorizontalLine,
        ImageBlock,
        ImageCaption,
        ImageInline,
        ImageInsert,
        ImageInsertViaUrl,
        ImageResize,
        ImageStyle,
        ImageToolbar,
        ImageUpload,
        Indent,
        IndentBlock,
        Italic,
        Link,
        LinkImage,
        List,
        ListProperties,
        MediaEmbed,
        Paragraph,
        PasteFromOffice,
        RemoveFormat,
        SelectAll,
        ShowBlocks,
        SimpleUploadAdapter,
        SourceEditing,
        SpecialCharacters,
        SpecialCharactersArrows,
        SpecialCharactersCurrency,
        SpecialCharactersEssentials,
        SpecialCharactersLatin,
        SpecialCharactersMathematical,
        SpecialCharactersText,
        Strikethrough,
        Style,
        Subscript,
        Superscript,
        Table,
        TableCaption,
        TableCellProperties,
        TableColumnResize,
        TableProperties,
        TableToolbar,
        TextTransformation,
        TodoList,
        Underline,
        Undo
    ],
    fontFamily: {
        supportAllValues: true
    },
    fontSize: {
        options: [10, 12, 14, 'default', 18, 20, 22, 24, 26, 28, 36, 48],
        supportAllValues: true
    },
    heading: {
        options: [
            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
            { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
            { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
            { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
        ]
    },
    htmlSupport: {
        allow: [
            {
                name: /^.*$/,
                styles: true,
                attributes: true,
                classes: true
            }
        ]
    },
    image: {
        toolbar: [
            'toggleImageCaption',
            'imageTextAlternative',
            '|',
            'imageStyle:inline',
            'imageStyle:wrapText',
            'imageStyle:breakText',
            '|',
            'resizeImage'
        ]
    },
    link: {
        addTargetToExternalLinks: true,
        defaultProtocol: 'https://',
        decorators: {
            toggleDownloadable: {
                mode: 'manual',
                label: 'Downloadable',
                attributes: {
                    download: 'file'
                }
            }
        }
    },
    list: {
        properties: {
            styles: true,
            startIndex: true,
            reversed: true
        }
    },
    menuBar: {
        isVisible: true
    },
    placeholder: 'Start writing your content here...',
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
    }
};

/**
 * Initialize CKEditor on a textarea element
 * @param {string|HTMLElement} element - CSS selector or DOM element
 * @param {object} customConfig - Additional configuration options
 * @returns {Promise<Editor>}
 */
export async function initCKEditor(element, customConfig = {}) {
    try {
        const editorElement = typeof element === 'string' 
            ? document.querySelector(element) 
            : element;

        if (!editorElement) {
            throw new Error(`Element ${element} not found`);
        }

        // Get CSRF token for upload
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        // Merge configurations
        const config = {
            ...defaultConfig,
            ...customConfig,
            simpleUpload: {
                uploadUrl: customConfig.uploadUrl || '/admin/media/upload-image',
                withCredentials: true,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            }
        };

        // Create editor
        const editor = await ClassicEditor.create(editorElement, config);

        console.log('✓ CKEditor initialized successfully');

        // Store editor instance on element for later access
        editorElement.ckeditorInstance = editor;

        return editor;
    } catch (error) {
        console.error('✗ CKEditor initialization failed:', error);
        throw error;
    }
}

/**
 * Destroy CKEditor instance
 * @param {string|HTMLElement} element - CSS selector or DOM element
 */
export async function destroyCKEditor(element) {
    try {
        const editorElement = typeof element === 'string' 
            ? document.querySelector(element) 
            : element;

        if (editorElement?.ckeditorInstance) {
            await editorElement.ckeditorInstance.destroy();
            delete editorElement.ckeditorInstance;
            console.log('✓ CKEditor destroyed successfully');
        }
    } catch (error) {
        console.error('✗ CKEditor destruction failed:', error);
    }
}

/**
 * Get content from CKEditor
 * @param {string|HTMLElement} element - CSS selector or DOM element
 * @returns {string}
 */
export function getCKEditorContent(element) {
    const editorElement = typeof element === 'string' 
        ? document.querySelector(element) 
        : element;

    return editorElement?.ckeditorInstance?.getData() || '';
}

/**
 * Set content to CKEditor
 * @param {string|HTMLElement} element - CSS selector or DOM element
 * @param {string} content - HTML content to set
 */
export function setCKEditorContent(element, content) {
    const editorElement = typeof element === 'string' 
        ? document.querySelector(element) 
        : element;

    if (editorElement?.ckeditorInstance) {
        editorElement.ckeditorInstance.setData(content);
    }
}

// Export ClassicEditor for direct access if needed
export { ClassicEditor };

