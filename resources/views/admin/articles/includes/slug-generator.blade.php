{{-- Slug Generator Script --}}
<script>
// Slug generator - dynamic based on locale
function generateSlug() {
    const locale = '{{ app()->getLocale() }}';
    const titleField = `title_${locale}`;
    const titleInput = document.querySelector(`[name="${titleField}"]`);
    
    if (!titleInput) return;
    
    const title = titleInput.value;
    const slug = title
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
    document.getElementById('slug').value = slug;
}
</script>
