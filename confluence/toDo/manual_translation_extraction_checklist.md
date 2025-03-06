# Manual Translation Extraction Checklist

This checklist outlines the steps for manually extracting hardcoded strings and converting them to use Laravel’s translation helpers.

## Steps

1. **Inventory Hardcoded Strings**
   - Review Blade templates in `resources/views` for user-facing text.
   - Check JavaScript and Alpine.js files (e.g., in `resources/js`) for hardcoded strings.
   - Document the file paths, context, and proposed translation keys for each string.

2. **Replacement Process**
   - Replace each identified hardcoded string with the translation helper syntax, for example:
     - Convert `"Case Details"` to `{{ __('cases.details') }}` in Blade files.
     - Ensure dynamic content and variable interpolations are not disrupted.
   - Flag any complex or dynamic strings for further manual review.

3. **Documentation & Logging**
   - Maintain a log of all manual modifications.
   - Note any items that require additional team review.

4. **Testing & Validation**
   - Manually test UI changes to ensure translations render correctly.
   - Validate that layout integrity is maintained, especially with longer Spanish text.

5. **Team Review**
   - Schedule a review meeting to verify all changes.
   - Finalize translation keys and update the corresponding files in `lang/en/` and `lang/es/`.

## Next Steps

- Begin the extraction process following the checklist.
- Update this checklist with any new findings or process improvements.

*This checklist will serve as the guide for the manual translation extraction and replacement work.*

## Status

- **Started on Core UI Elements:** Extraction of navigation bar, dashboard labels, and button text is complete.
- **In Progress:** Reviewing and extracting form labels, error messages, and success notifications.
- **Pending:** Extraction for feature-specific and system content.