## Common Form Components
The following Blade components should be available in all forms:
- `<x-input-label>` - For form input labels
- `<x-input-error>` - For displaying validation errors
- `<x-text-input>` - For text input fields
- `<x-textarea>` - For multiline text input
- `<x-select>` - For dropdown select fields
- `<x-checkbox>` - For checkbox inputs
- `<x-radio>` - For radio inputs
- `<x-button>` - For form submission and actions

Usage example:
```blade
<div>
    <x-input-label for="field_name" :value="__('label.text')" />
    <x-text-input 
        id="field_name"
        type="text"
        class="mt-1 block w-full"
        :error="$errors->has('field_name')"
    />
    <x-input-error :messages="$errors->get('field_name')" />
</div>
```

When creating new features, ensure all required components are present and consistent with the existing design system.

# Additional Rules:

## Translation Rules
### Storage Location
- All translations MUST be stored in `resources/lang/{locale}/*.php`
- Each feature should have its own translation file
- Common/shared translations go in `app.php`
- Form-related translations go in `forms.php`
- Validation messages go in `validation.php`

### File Organization
```
resources/lang/
├── en/
│   ├── app.php           # Global application strings
│   ├── auth.php          # Authentication related
│   ├── forms.php         # Common form labels/messages
│   ├── validation.php    # Validation messages
│   ├── notifications.php # System notifications
│   └── features/         # Feature-specific translations
│       ├── docket.php    # Docket feature strings
│       ├── cases.php     # Case management strings
│       └── documents.php # Document management strings
└── es/
    └── [same structure as en/]
```

### Key Naming Conventions
- Use dot notation for nesting: `feature.context.key`
- Group related translations under common prefixes
- Use descriptive, consistent key names
- Avoid generic keys like `title` or `description`
- Include context in key names when necessary

### Examples
```php
// Good
'docket.entry.create' => 'Create Entry'
'cases.details.title' => 'Case Details'
'documents.upload.drag_drop' => 'Drag and drop files'

// Bad
'title' => 'Title'
'create' => 'Create'
'message' => 'Success'
```

## Component Rules
- each Livewire component must have its own directory under `app/Livewire`
- component properties must be explicitly typed
- use `wire:loading` states for all async operations
- implement proper validation messages for all forms

## Security Rules
- all user input must be validated
- use typed properties for Livewire components to prevent mass assignment
- implement proper authorization checks using Laravel policies
- sensitive operations must be confirmed using modals

## UI/UX Rules
- all forms must show loading states during submission
- all async operations must show feedback to users
- error messages must be translated and user-friendly
- modals must be closeable via escape key and clicking outside
- use the banner component for important user feedback (success/error messages)
  - Success messages after create/update/delete operations
  - Error messages when operations fail
  - Warning messages for important user notifications

## Code Organization
- keep Livewire components focused and single-purpose
- extract reusable logic into traits
- use dedicated form request classes for complex validation
- maintain consistent naming conventions across components

## Testing
- all new features must include tests
- test both success and failure scenarios
- include translation tests for new user-facing text
- test authorization rules for all new features

## Documentation
- document all new features in `confluence/features`
- update technical documentation when changing architecture
- include examples for complex component interactions
- document any deviations from standard patterns

## Page Creation Rules
- New features should follow this structure:
  1. Create a controller in `app/Http/Controllers`
  2. Create a blade view in `resources/views/{feature}`
  3. Create Livewire components in `app/Livewire/{Feature}`
  4. Register route in `routes/web.php` using the controller

- DO NOT route directly to Livewire components in `routes/web.php`
  ```php
  // ❌ Don't do this:
  Route::get('/feature/{model}', \App\Livewire\Feature\FeatureComponent::class);
  
  // ✅ Do this instead:
  Route::get('/feature/{model}', [\App\Http\Controllers\FeatureController::class, 'index']);
  ```

- Standard Controller Structure:
  ```php
  class FeatureController extends Controller
  {
      public function index(Model $model)
      {
          return view('feature.index', [
              'model' => $model
          ]);
      }
  }
  ```

- Standard View Structure:
  ```blade
  <x-app-layout>
      <x-slot name="header">
          <h2 class="text-xl font-semibold leading-tight text-base-content/80">
              {{ __('feature.title') }}
          </h2>
      </x-slot>

      <div class="py-12">
          <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
              <livewire:feature.feature-component :model="$model" />
          </div>
      </div>
  </x-app-layout>
  ```

## File Organization
- Controllers: `app/Http/Controllers/{Feature}Controller.php`
- Views: `resources/views/{feature}/{action}.blade.php`
- Livewire Components: `app/Livewire/{Feature}/{ComponentName}.php`
- Livewire Views: `resources/views/livewire/{feature}/{component-name}.blade.php`
