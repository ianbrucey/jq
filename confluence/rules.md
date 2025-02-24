# Frontend Development Rules

## Alpine.js Component Registration

### Rule: Register Alpine.js Components in the `<x-alpine-scripts />` Component

All Alpine.js component definitions should be registered in the centralized `<x-alpine-scripts />` component rather than inline within individual Blade files. This ensures components are properly initialized regardless of when they are loaded in the DOM.

#### Why?

1. **Predictable Initialization**: Alpine components registered globally are available when the page loads, preventing initialization errors with dynamic/conditional content
2. **Prevents Duplicate Definitions**: Centralizing component definitions avoids multiple or conflicting definitions
3. **Better Performance**: Registering components once is more efficient than redefining them in multiple places
4. **Improved Maintainability**: Easier to find, update, and debug Alpine components when they're in a centralized location

#### ✅ Correct Implementation

1. Add your Alpine.js component definition to `resources/views/components/alpine-scripts.blade.php`:

```javascript
document.addEventListener('alpine:init', () => {
    Alpine.data('documentUploader', ({ files = [], titles = [], descriptions = [] } = {}) => ({
        // Component properties and methods here
    }));
    
    // Other Alpine.js components
});

<div x-data="documentUploader({
    files: $wire.entangle('queuedFiles'),
    titles: $wire.entangle('documentTitles'),
    descriptions: $wire.entangle('documentDescriptions')
})">
    <!-- Component markup -->
</div>

<!-- Don't do this -->
<div x-data="...">
    <!-- Component markup -->
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('myComponent', () => ({
            // Component definition here
        }));
    });
</script>
