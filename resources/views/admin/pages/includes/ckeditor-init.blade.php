{{-- CKEditor Initialization Script for Pages --}}
<script>
document.addEventListener('DOMContentLoaded', async function() {
    console.log('DOM loaded, looking for CKEditor...');
    
    if (typeof window.initCKEditor === 'function') {
        console.log('initCKEditor function available');
        const textarea = document.querySelector('[name="content_{{ app()->getLocale() }}"]');
        console.log('Textarea found:', textarea);
        
        if (textarea) {
            try {
                console.log('Initializing CKEditor for textarea:', textarea);
                await window.initCKEditor(textarea, {
                    placeholder: '{{ __("admin.pages.content_placeholder") }}',
                    uploadUrl: '{{ route("admin.upload.image") }}'
                });
                console.log('CKEditor initialized successfully for pages');
            } catch (error) {
                console.error('Error initializing CKEditor for pages:', error);
            }
        } else {
            console.error('Textarea not found with selector: [name="content_{{ app()->getLocale() }}"]');
        }
    } else {
        console.warn('CKEditor not available for pages');
    }
});
</script>
