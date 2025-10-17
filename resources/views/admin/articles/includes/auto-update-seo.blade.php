{{-- Auto-update Slug and SEO Script --}}
<script>
// Auto-update slug and SEO when title changes (REAL-TIME)
function autoUpdateSlugAndSEO() {
    const locale = '{{ app()->getLocale() }}';
    const titleField = `title_${locale}`;
    const excerptField = `excerpt_${locale}`;
    const contentField = `content_${locale}`;
    
    const titleInput = document.querySelector(`[name="${titleField}"]`);
    const excerptInput = document.querySelector(`[name="${excerptField}"]`);
    const slugField = document.getElementById('slug');
    const metaTitleField = document.getElementById('meta_title');
    const metaDescField = document.getElementById('meta_description');
    
    if (titleInput) {
        // Track if user has manually edited fields
        let userEditedSlug = false;
        let userEditedMetaTitle = false;
        let userEditedMetaDesc = false;
        
        // Mark fields as user-edited when they change
        if (slugField) {
            slugField.addEventListener('input', () => userEditedSlug = true);
        }
        if (metaTitleField) {
            metaTitleField.addEventListener('input', () => userEditedMetaTitle = true);
        }
        if (metaDescField) {
            metaDescField.addEventListener('input', () => userEditedMetaDesc = true);
        }
        
        titleInput.addEventListener('input', function() {
            const title = this.value;
            
            // Auto-update slug in real-time (unless user manually edited)
            if (slugField && !userEditedSlug) {
                const slug = title
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
                slugField.value = slug;
                
                // Visual feedback
                slugField.style.backgroundColor = '#f0f9ff';
                setTimeout(() => {
                    slugField.style.backgroundColor = '';
                }, 500);
            }
            
            // Auto-update meta title in real-time (unless user manually edited)
            if (metaTitleField && !userEditedMetaTitle) {
                metaTitleField.value = title.substring(0, 60);
                
                // Visual feedback
                metaTitleField.style.backgroundColor = '#f0f9ff';
                setTimeout(() => {
                    metaTitleField.style.backgroundColor = '';
                }, 500);
            }
            
            // Auto-update meta description in real-time (unless user manually edited)
            if (metaDescField && !userEditedMetaDesc) {
                const excerpt = excerptInput ? excerptInput.value : '';
                const description = excerpt || title;
                metaDescField.value = description.substring(0, 160);
                
                // Visual feedback
                metaDescField.style.backgroundColor = '#f0f9ff';
                setTimeout(() => {
                    metaDescField.style.backgroundColor = '';
                }, 500);
            }
        });
        
        // Also update when excerpt changes (for meta description)
        if (excerptInput) {
            excerptInput.addEventListener('input', function() {
                const excerpt = this.value;
                const title = titleInput.value;
                
                if (metaDescField && !userEditedMetaDesc) {
                    const description = excerpt || title;
                    metaDescField.value = description.substring(0, 160);
                    
                    // Visual feedback
                    metaDescField.style.backgroundColor = '#f0f9ff';
                    setTimeout(() => {
                        metaDescField.style.backgroundColor = '';
                    }, 500);
                }
            });
        }
    }
}

// Initialize auto-update on page load
document.addEventListener('DOMContentLoaded', function() {
    autoUpdateSlugAndSEO();
});
</script>
