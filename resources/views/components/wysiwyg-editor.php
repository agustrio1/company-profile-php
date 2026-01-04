<!-- WYSIWYG Editor - Quill v2 with Image Resize -->

<!-- Editor Container -->
<div id="quill-editor-container">
    <div id="quill-editor" style="min-height: 300px; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem;"></div>
    <input type="hidden" id="editor-content" name="content" value="">
</div>

<!-- Quill CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

<!-- Quill JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

<!-- Quill Resize Image Module -->
<script defer src="https://cdn.jsdelivr.net/gh/hunghg255/quill-resize-module/dist/quill-resize-image.min.js"></script>

<script>
(function() {
    // Tunggu DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initQuill);
    } else {
        initQuill();
    }
    
    function initQuill() {
        const editorEl = document.getElementById('quill-editor');
        if (!editorEl) return;
        
        // Wait for QuillResizeImage to load
        if (!window.QuillResizeImage) {
            setTimeout(initQuill, 100);
            return;
        }
        
        // Register Resize Module
        Quill.register('modules/resize', window.QuillResizeImage);
        
        // Toolbar configuration
        const toolbarOptions = [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'size': ['small', false, 'large', 'huge'] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'align': [] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            ['blockquote', 'code-block'],
            ['link', 'image'],
            ['clean']
        ];
        
        // Initialize Quill with Resize Module
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Tulis konten blog di sini...',
            modules: {
                toolbar: {
                    container: toolbarOptions
                },
                resize: {
                    locale: {
                        altTip: 'Alt text',
                        floatLeft: 'Rata Kiri',
                        floatRight: 'Rata Kanan',
                        center: 'Tengah',
                        restore: 'Reset Ukuran'
                    }
                }
            }
        });

        // Load existing content
        const contentInput = document.getElementById('editor-content');
        
        // Get content from PHP variable if exists
        const existingContent = <?= json_encode(isset($editorContent) ? $editorContent : (old('content', isset($blog->content) ? $blog->content : ''))) ?>;
        
        if (existingContent) {
            quill.root.innerHTML = existingContent;
            contentInput.value = existingContent;
        }

        // Update hidden input on change
        quill.on('text-change', function() {
            contentInput.value = quill.root.innerHTML;
        });

        // Set initial value
        contentInput.value = quill.root.innerHTML;

        // Custom image handler
        const toolbar = quill.getModule('toolbar');
        toolbar.addHandler('image', function() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            
            input.onchange = async function() {
                const file = input.files[0];
                if (!file) return;

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Harus file gambar!');
                    return;
                }
                
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Maksimal 5MB!');
                    return;
                }

                const formData = new FormData();
                formData.append('image', file);

                // Get cursor position
                const range = quill.getSelection(true);
                
                // Insert loading text
                quill.insertText(range.index, 'Uploading image...');
                quill.setSelection(range.index + 18);

                try {
                    const response = await fetch('<?= url("api/upload-image") ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Server tidak mengembalikan JSON. Kemungkinan ada error PHP.');
                    }

                    const result = await response.json();

                    // Remove loading text
                    quill.deleteText(range.index, 18);

                    if (result.success && result.url) {
                        // Insert image at cursor position
                        quill.insertEmbed(range.index, 'image', result.url);
                        
                        // Move cursor after image
                        quill.setSelection(range.index + 1);
                        
                        console.log('Image uploaded successfully:', result);
                    } else {
                        alert('Upload gagal: ' + (result.message || 'Unknown error'));
                        console.error('Upload error:', result);
                    }
                } catch (error) {
                    // Remove loading text on error
                    try {
                        quill.deleteText(range.index, 18);
                    } catch (e) {
                        console.error('Error removing loading text:', e);
                    }
                    
                    console.error('Upload error:', error);
                    alert('Upload gagal! ' + error.message);
                }
            };
            
            input.click();
        });

        window.quillEditor = quill;
    }
})();
</script>

<style>
/* Quill Editor Styling - Scoped ke container */
#quill-editor-container .ql-toolbar.ql-snow {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem 0.5rem 0 0;
    padding: 8px;
}

#quill-editor-container .ql-container.ql-snow {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-top: none;
    border-radius: 0 0 0.5rem 0.5rem;
}

#quill-editor-container .ql-editor {
    min-height: 300px;
    max-height: 600px;
    overflow-y: auto;
    font-size: 1rem;
    line-height: 1.6;
    color: #111827;
}

#quill-editor-container .ql-editor.ql-blank::before {
    color: #9ca3af;
    font-style: normal;
}

/* Typography - Scoped */
#quill-editor-container .ql-editor h1 { font-size: 2em; font-weight: bold; margin: 0.67em 0; }
#quill-editor-container .ql-editor h2 { font-size: 1.5em; font-weight: bold; margin: 0.75em 0; }
#quill-editor-container .ql-editor h3 { font-size: 1.17em; font-weight: bold; margin: 0.83em 0; }
#quill-editor-container .ql-editor p { margin-bottom: 0.5em; }
#quill-editor-container .ql-editor a { color: #2563eb; text-decoration: underline; }

/* Image Styling - WordPress style */
#quill-editor-container .ql-editor img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 1em 0;
    cursor: pointer;
    transition: all 0.2s ease;
}

/* Image float alignment */
#quill-editor-container .ql-editor img[style*="float: left"] {
    float: left;
    margin: 0.5em 1em 0.5em 0;
}

#quill-editor-container .ql-editor img[style*="float: right"] {
    float: right;
    margin: 0.5em 0 0.5em 1em;
}

#quill-editor-container .ql-editor img[style*="display: block"][style*="margin-left: auto"] {
    display: block;
    margin-left: auto;
    margin-right: auto;
}

/* Resize Module Styling */
.quill-resize-img {
    position: relative;
}

.quill-resize-img.active {
    border: 2px dashed #3b82f6;
}

.quill-resize-handle {
    position: absolute;
    width: 10px;
    height: 10px;
    background: #3b82f6;
    border: 2px solid white;
    border-radius: 50%;
    cursor: nwse-resize;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.quill-resize-handle-nw { top: -5px; left: -5px; cursor: nwse-resize; }
.quill-resize-handle-ne { top: -5px; right: -5px; cursor: nesw-resize; }
.quill-resize-handle-sw { bottom: -5px; left: -5px; cursor: nesw-resize; }
.quill-resize-handle-se { bottom: -5px; right: -5px; cursor: nwse-resize; }

/* Toolbar for image actions */
.quill-resize-toolbar {
    position: absolute;
    top: -45px;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    padding: 6px;
    display: flex;
    gap: 6px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 100;
}

.quill-resize-toolbar button {
    padding: 6px 12px;
    border: none;
    background: #f3f4f6;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 500;
    color: #374151;
    transition: all 0.2s;
    white-space: nowrap;
}

.quill-resize-toolbar button:hover {
    background: #e5e7eb;
}

.quill-resize-toolbar button.active {
    background: #3b82f6;
    color: white;
}

/* Size display */
.quill-resize-size-display {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    pointer-events: none;
    white-space: nowrap;
}

#quill-editor-container .ql-editor blockquote { border-left: 4px solid #e5e7eb; padding-left: 1em; margin: 1em 0; font-style: italic; color: #6b7280; }
#quill-editor-container .ql-editor pre { background-color: #f3f4f6; border: 1px solid #e5e7eb; padding: 1em; border-radius: 0.375rem; overflow-x: auto; }
#quill-editor-container .ql-editor code { background-color: #f3f4f6; padding: 0.2em 0.4em; border-radius: 0.25rem; font-size: 0.875em; color: #ef4444; }

/* Mobile */
@media (max-width: 640px) {
    #quill-editor-container .ql-toolbar.ql-snow { padding: 6px; }
    #quill-editor-container .ql-editor { font-size: 0.875rem; }
    
    /* Stack floated images on mobile */
    #quill-editor-container .ql-editor img[style*="float"] {
        float: none !important;
        display: block;
        margin: 1em auto;
    }
    
    .quill-resize-toolbar {
        top: auto;
        bottom: -50px;
    }
}
</style>