{{-- CKEditor Initialization Script for Pages --}}
<script>
document.addEventListener('DOMContentLoaded', async function() {
    if (typeof window.initCKEditor === 'function') {
        const textarea = document.querySelector('[name="content_{{ app()->getLocale() }}"]');
        if (textarea) {
            try {
                await window.initCKEditor(textarea, {
                    placeholder: '{{ __("admin.pages.content_placeholder") }}',
                    uploadUrl: '{{ route("admin.upload.image") }}'
                });
                console.log('CKEditor initialized successfully for pages');
            } catch (error) {
                console.error('Error initializing CKEditor for pages:', error);
            }
        }
    } else {
        console.warn('CKEditor not available for pages');
    }
});
</script>
