{{-- CKEditor Initialization Script --}}
<script>
// Initialize CKEditor (loaded from local via Vite)
document.addEventListener('DOMContentLoaded', async function() {
    console.log('Initializing CKEditor...');
    
    if (typeof window.initCKEditor === 'function') {
        try {
            const locale = '{{ app()->getLocale() }}';
            const contentField = `content_${locale}`;
            const textarea = document.querySelector(`[name="${contentField}"]`);
            
            if (textarea) {
                await window.initCKEditor(textarea, {
                    uploadUrl: '{{ route("admin.upload.image") }}',
                    placeholder: '{{ __("admin.articles.content_label") }}...'
                });
                console.log('CKEditor initialized successfully');
            } else {
                console.warn('Content textarea not found');
            }
        } catch (error) {
            console.error('Error initializing CKEditor:', error);
        }
    } else {
        console.error('initCKEditor function not found. Check if app.js is loaded.');
    }
});
</script>
