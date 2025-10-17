{{-- Slug Generator Script for Pages --}}
<script>
function generateSlug() {
    const titleField = document.querySelector('[name="title_{{ app()->getLocale() }}"]');
    const slugField = document.querySelector('[name="slug"]');
    
    if (titleField && slugField) {
        const title = titleField.value;
        const slug = title
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-') // Replace multiple hyphens with single
            .trim('-'); // Remove leading/trailing hyphens
        
        slugField.value = slug;
    }
}
</script>
