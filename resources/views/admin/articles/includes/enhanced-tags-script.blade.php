{{-- Enhanced Tags Management Script --}}
<script>
// Enhanced Tags Management
let newTags = []; // Store new tags to be created
let selectedTags = []; // Store selected tag IDs

// Handle tag input (Enter key)
function handleTagInput(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        addNewTag();
    }
}

// Add new tag
function addNewTag() {
    const input = document.getElementById('new_tag_input');
    const tagName = input.value.trim();
    
    if (tagName) {
        // Add to new tags array
        newTags.push(tagName);
        
        // Create hidden input for form submission
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'new_tags[]';
        hiddenInput.value = tagName;
        hiddenInput.id = `new_tag_${newTags.length}`;
        document.getElementById('new_tags_inputs').appendChild(hiddenInput);
        
        // Create visual tag element
        const tagElement = document.createElement('span');
        tagElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        tagElement.innerHTML = `
            ${tagName} (new)
            <button type="button" onclick="removeNewTag('${tagName}')" class="ml-2 text-green-600 hover:text-green-800">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        document.getElementById('selected_tags_list').appendChild(tagElement);
        input.value = '';
        updateTagCount();
    }
}

// Remove new tag
function removeNewTag(tagName) {
    newTags = newTags.filter(tag => tag !== tagName);
    
    // Remove hidden input
    const hiddenInputs = document.querySelectorAll('input[name="new_tags[]"]');
    hiddenInputs.forEach(input => {
        if (input.value === tagName) {
            input.remove();
        }
    });
    
    updateSelectedTagsDisplay();
}

// Toggle existing tag
function toggleTag(checkbox, tagId, tagName) {
    if (checkbox.checked) {
        if (!selectedTags.includes(tagId)) {
            selectedTags.push(tagId);
        }
    } else {
        selectedTags = selectedTags.filter(id => id !== tagId);
    }
    updateSelectedTagsDisplay();
}

// Update selected tags display
function updateSelectedTagsDisplay() {
    const container = document.getElementById('selected_tags_list');
    const countElement = document.getElementById('tag_count');
    
    // Clear container
    container.innerHTML = '';
    
    // Add new tags
    newTags.forEach(tagName => {
        const tagElement = document.createElement('span');
        tagElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        tagElement.innerHTML = `
            ${tagName} (new)
            <button type="button" onclick="removeNewTag('${tagName}')" class="ml-2 text-green-600 hover:text-green-800">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        container.appendChild(tagElement);
    });
    
    // Add selected existing tags
    selectedTags.forEach(tagId => {
        const checkbox = document.querySelector(`input[name="tags[]"][value="${tagId}"]`);
        if (checkbox) {
            const tagName = checkbox.parentElement.querySelector('span').textContent.trim();
            const tagElement = document.createElement('span');
            tagElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
            tagElement.innerHTML = `
                ${tagName}
                <button type="button" onclick="removeSelectedTag(${tagId})" class="ml-2 text-blue-600 hover:text-blue-800">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            container.appendChild(tagElement);
        }
    });
    
    updateTagCount();
}

// Remove selected tag
function removeSelectedTag(tagId) {
    selectedTags = selectedTags.filter(id => id !== tagId);
    const checkbox = document.querySelector(`input[name="tags[]"][value="${tagId}"]`);
    if (checkbox) {
        checkbox.checked = false;
    }
    updateSelectedTagsDisplay();
}

// Update tag count
function updateTagCount() {
    const totalTags = newTags.length + selectedTags.length;
    document.getElementById('tag_count').textContent = totalTags;
}

// Generate auto tags
function generateAutoTags() {
    const locale = '{{ app()->getLocale() }}';
    const titleField = `title_${locale}`;
    const contentField = `content_${locale}`;
    const excerptField = `excerpt_${locale}`;
    
    const titleInput = document.querySelector(`[name="${titleField}"]`);
    const contentInput = document.querySelector(`[name="${contentField}"]`);
    const excerptInput = document.querySelector(`[name="${excerptField}"]`);
    
    if (!titleInput) {
        alert('{{ __("admin.articles.title_required_for_auto_tags") }}');
        return;
    }
    
    const title = titleInput.value;
    const content = contentInput ? contentInput.value : '';
    const excerpt = excerptInput ? excerptInput.value : '';
    
    if (!title.trim()) {
        alert('{{ __("admin.articles.title_required_for_auto_tags") }}');
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>{{ __("admin.articles.generating_tags") }}';
    button.disabled = true;
    
    // Simulate auto tag generation (in real app, this would be an AJAX call)
    setTimeout(() => {
        // Sample auto-generated tags based on content
        const sampleTags = ['Laravel', 'PHP', 'Tutorial', 'Web Development'];
        
        sampleTags.forEach(tagName => {
            if (!newTags.includes(tagName) && !selectedTags.some(id => {
                const checkbox = document.querySelector(`input[name="tags[]"][value="${id}"]`);
                return checkbox && checkbox.parentElement.querySelector('span').textContent.trim() === tagName;
            })) {
                newTags.push(tagName);
                
                // Create hidden input for form submission
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'new_tags[]';
                hiddenInput.value = tagName;
                hiddenInput.id = `new_tag_${newTags.length}`;
                document.getElementById('new_tags_inputs').appendChild(hiddenInput);
            }
        });
        
        updateSelectedTagsDisplay();
        
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
        
        alert('{{ __("admin.articles.auto_tags_generated") }}');
    }, 1500);
}

// Suggest tags
function suggestTags() {
    const locale = '{{ app()->getLocale() }}';
    const titleField = `title_${locale}`;
    const titleInput = document.querySelector(`[name="${titleField}"]`);
    
    if (!titleInput || !titleInput.value.trim()) {
        alert('{{ __("admin.articles.title_required_for_suggestions") }}');
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>{{ __("admin.articles.suggesting_tags") }}';
    button.disabled = true;
    
    // Simulate tag suggestions (in real app, this would be an AJAX call)
    setTimeout(() => {
        // Sample suggested tags
        const suggestedTags = ['JavaScript', 'Vue.js', 'React', 'CSS', 'HTML'];
        
        let suggestions = '';
        suggestedTags.forEach(tagName => {
            const isSelected = selectedTags.some(id => {
                const checkbox = document.querySelector(`input[name="tags[]"][value="${id}"]`);
                return checkbox && checkbox.parentElement.querySelector('span').textContent.trim() === tagName;
            });
            
            if (!isSelected && !newTags.includes(tagName)) {
                suggestions += `<div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                    <span class="text-sm text-gray-700 dark:text-gray-300">${tagName}</span>
                    <button type="button" onclick="addSuggestedTag('${tagName}')" class="text-blue-600 hover:text-blue-800 text-sm">
                        {{ __("admin.articles.add") }}
                    </button>
                </div>`;
            }
        });
        
        if (suggestions) {
            // Show suggestions in a modal or alert
            alert('{{ __("admin.articles.suggested_tags") }}:\n' + suggestedTags.join(', '));
        } else {
            alert('{{ __("admin.articles.no_suggestions") }}');
        }
        
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
    }, 1000);
}

// Add suggested tag
function addSuggestedTag(tagName) {
    if (!newTags.includes(tagName)) {
        newTags.push(tagName);
        
        // Create hidden input for form submission
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'new_tags[]';
        hiddenInput.value = tagName;
        hiddenInput.id = `new_tag_${newTags.length}`;
        document.getElementById('new_tags_inputs').appendChild(hiddenInput);
        
        updateSelectedTagsDisplay();
    }
}

// Initialize enhanced tags on page load
document.addEventListener('DOMContentLoaded', function() {
    updateSelectedTagsDisplay();
});
</script>
