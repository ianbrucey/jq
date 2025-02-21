
# Implementing a Rich Text Editor with Laravel Livewire

This guide explains how to implement a rich text editor using Laravel Livewire and TipTap's vanilla JavaScript implementation.

## Prerequisites

- Laravel 10.x
- Livewire 3.x
- Composer
- NPM

## Installation Steps

### 1. Install Required Packages

```bash
composer require livewire/livewire
npm install @tiptap/core @tiptap/starter-kit @tiptap/extension-text-align @tiptap/extension-text-style
```

### 2. Create Livewire Component

Create a new Livewire component for the editor:

```bash
php artisan make:livewire TextEditor
```

### 3. Component Implementation

Update `app/Livewire/TextEditor.php`:

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class TextEditor extends Component
{
    public $content = '';
    
    public function updateContent($newContent)
    {
        $this->content = $newContent;
    }

    public function save()
    {
        // Save content to database
        $this->dispatch('saved');
    }

    public function render()
    {
        return view('livewire.text-editor');
    }
}
```

### 4. Component View

Create `resources/views/livewire/text-editor.blade.php`:

```php
<div
    x-data="{
        editor: null,
        init() {
            this.editor = new Editor({
                element: this.$refs.element,
                extensions: [
                    StarterKit,
                    TextStyle,
                    TextAlign.configure({
                        types: ['heading', 'paragraph']
                    })
                ],
                content: @entangle('content'),
                onUpdate: ({ editor }) => {
                    $wire.updateContent(editor.getHTML())
                }
            })
        }
    }"
    class="w-full"
>
    <!-- Toolbar -->
    <div class="border-b p-2 flex flex-wrap gap-1 bg-gray-100">
        <button 
            @click="editor.chain().focus().toggleBold().run()"
            :class="{ 'bg-gray-200': editor.isActive('bold') }"
            class="p-2 rounded hover:bg-gray-200"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24">
                <path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"></path>
                <path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"></path>
            </svg>
        </button>
        
        <!-- Add other toolbar buttons similarly -->
        
        <button 
            @click="editor.chain().focus().setTextAlign('left').run()"
            :class="{ 'bg-gray-200': editor.isActive({ textAlign: 'left' }) }"
            class="p-2 rounded hover:bg-gray-200"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24">
                <path d="M3 5h18M3 12h12M3 19h6"></path>
            </svg>
        </button>
        
        <select 
            @change="editor.chain().focus().setFontSize($event.target.value).run()"
            class="px-2 py-1 rounded border"
        >
            <option value="12">12pt</option>
            <option value="14">14pt</option>
            <option value="16">16pt</option>
            <option value="18">18pt</option>
            <option value="20">20pt</option>
        </select>
    </div>

    <!-- Editor Content -->
    <div 
        x-ref="element"
        class="prose max-w-none p-4"
        wire:ignore
    ></div>

    <!-- Save Button -->
    <div class="border-t p-2">
        <button
            wire:click="save"
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
        >
            Save
        </button>
    </div>
</div>
```

### 5. Add JavaScript

Create `resources/js/editor.js`:

```javascript
import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import TextAlign from '@tiptap/extension-text-align'
import TextStyle from '@tiptap/extension-text-style'

window.Editor = Editor
window.StarterKit = StarterKit
window.TextAlign = TextAlign
window.TextStyle = TextStyle
```

### 6. Update Layout

Add to your layout file (`resources/views/layouts/app.blade.php`):

```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rich Text Editor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    {{ $slot }}
    
    @livewireScripts
</body>
</html>
```

### 7. Add Styles

In `resources/css/app.css`:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

.ProseMirror {
    min-height: 11in;
    background: white;
    padding: 1in;
    box-sizing: border-box;
}

.ProseMirror p {
    line-height: 2;
}

.ProseMirror:focus {
    outline: none;
}
```

### 8. Create Route

In `routes/web.php`:

```php
Route::get('/editor', function () {
    return view('editor');
});
```

Create `resources/views/editor.blade.php`:

```php
<x-app-layout>
    <div class="container mx-auto py-8">
        <livewire:text-editor />
    </div>
</x-app-layout>
```

## Key Differences from React Implementation

1. Uses Livewire for server-side rendering and state management
2. Integrates Alpine.js for client-side interactivity
3. More PHP-centric approach
4. Direct DOM manipulation through TipTap's vanilla JS implementation
5. No need for React components and hooks
6. Simpler build process
7. Smaller bundle size

## Security Considerations

- Implement CSRF protection (included by default in Laravel)
- Sanitize HTML content before storing and displaying
- Add proper user authentication
- Use Laravel's built-in XSS protection

## Benefits of Livewire Approach

1. Simpler debugging (PHP-based)
2. Faster initial page load
3. Less JavaScript to maintain
4. Better integration with Laravel ecosystem
5. Progressive enhancement support
6. Easier server-side validation
