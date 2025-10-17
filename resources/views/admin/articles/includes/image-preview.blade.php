{{-- Image Preview Script --}}
<script>
// Image preview
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Remove image
function removeImage() {
    const fileInput = document.getElementById('featured_image');
    const preview = document.getElementById('imagePreview');
    
    if (fileInput) {
        fileInput.value = '';
    }
    if (preview) {
        preview.classList.add('hidden');
    }
}
</script>
