- all livewire components should be in the `app/Livewire` directory
- us daisyUI for styling
- refer to database_schema.md for database structure
- refer to about_justice_quest.md for project overview
- we store information about features in confluence/features
- when you add a new field to a model, make sure that you update the fillable array on the class
- we are using laravel 11 and Livewire 3
- when working on a feature or a bug, do not touch any other functionality or elements, unless absolutely necessary to accomplish the task
- any migrations you decide to create should have a file name with a date later than the last migration created. You should also provide the command to create the migration 
- use the built-in banner component (`<x-banner>`) for user feedback after important actions. Set using:
  ```php
  session()->flash('flash.banner', __('translation.key'));
  session()->flash('flash.bannerStyle', 'success'); // success, danger, warning
  ```

# Additional Rules:

## Translation Rules
- all user-facing text must use translation keys (no hardcoded strings)
- translation keys should follow the format: `feature.context.key`
- when adding new translations, update both `lang/en` and `lang/es` files

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
