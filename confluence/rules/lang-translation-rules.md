# specific rules for multilingual implementation to the development rules file:

### Multilingual Implementation Standards
**Rule**: All user-facing text must follow these guidelines:
- No hardcoded strings in views or components
- All translations must be organized by feature/context
- Use dot notation for nested translations (e.g., `voice.start_recording`)
- Maintain consistent key naming across language files
- Group related translations in context-specific files (e.g., `voice.php`, `forms.php`)

**Implementation Requirements**:
1. Translation Files:
    - One file per feature/context
    - Clear, descriptive key names
    - Comments for complex or context-dependent translations
    - Placeholder documentation where applicable

2. View Files:
    - Use `__()` or `trans()` helpers exclusively
    - No string concatenation in translation keys
    - Use translation parameters for dynamic content
    - Include placeholder translations in default language

3. Components:
    - Pass translatable content through props when needed
    - Use consistent naming for translation props
    - Document translation requirements in component headers

4. JavaScript/Alpine:
    - Use `x-text` with translation helpers for dynamic content
    - Maintain translation keys in dedicated language files
    - No hardcoded strings in x-data or event handlers

**Example Structure**:
```php
// Correct
{{ __('voice.start_recording') }}
x-text="recording ? '{{ __('voice.stop_recording') }}' : '{{ __('voice.start_recording') }}'"

// Incorrect
<span>Start Recording</span>
x-text="recording ? 'Stop' : 'Start'"
```

**Rationale**: Consistent translation implementation ensures maintainability, scalability, and reliable language switching across the application.

### Translation File Organization
**Rule**: Translation files must follow this structure:
```
lang/
├── en/
│   ├── app.php      # Global application strings
│   ├── auth.php     # Authentication related
│   ├── forms.php    # Form labels and messages
│   ├── voice.php    # Voice-related features
│   └── feature/     # Feature-specific translations
└── es/
    └── ...
```

**Code Review Checklist for Translations**:
1. All new strings are translated
2. Keys follow naming conventions
3. Placeholders are properly documented
4. No hardcoded strings in views
5. Translation files are properly organized
6. Dynamic content uses parameters correctly

**Testing Requirements**:
1. Verify translations in all supported languages
2. Test string length variations
3. Validate placeholder replacement
4. Check RTL support where applicable
   </augment_code_snippet>

These rules:
1. Establish clear standards for implementing translations
2. Provide specific examples of correct/incorrect usage
3. Define file organization structure
4. Include review and testing requirements
5. Maintain consistency with our current implementation

This will help ensure future work on multilingual features maintains the same quality and consistency we've established.
