import os
import re
from pathlib import Path

# Color class mappings
REPLACEMENTS = {
    # Background colors
    r'bg-white\b': 'bg-base-100',
    r'bg-gray-50\b': 'bg-base-200',
    r'bg-gray-100\b': 'bg-base-200',
    r'bg-gray-200\b': 'bg-base-200',
    r'bg-gray-300\b': 'bg-base-300',
    r'bg-gray-400\b': 'bg-base-300',
    r'bg-gray-500\b': 'bg-neutral',
    r'bg-gray-600\b': 'bg-neutral',
    r'bg-gray-700\b': 'bg-neutral-focus',
    r'bg-gray-800\b': 'bg-neutral-focus',
    r'bg-gray-900\b': 'bg-neutral-focus',
    
    # Text colors
    r'text-white\b': 'text-base-100',
    r'text-gray-50\b': 'text-base-content/90',
    r'text-gray-100\b': 'text-base-content/90',
    r'text-gray-200\b': 'text-base-content/80',
    r'text-gray-300\b': 'text-base-content/70',
    r'text-gray-400\b': 'text-base-content/60',
    r'text-gray-500\b': 'text-base-content/50',
    r'text-gray-600\b': 'text-base-content/60',
    r'text-gray-700\b': 'text-base-content/70',
    r'text-gray-800\b': 'text-base-content/80',
    r'text-gray-900\b': 'text-base-content',
    
    # Border colors
    r'border-gray-50\b': 'border-base-content/10',
    r'border-gray-100\b': 'border-base-content/10',
    r'border-gray-200\b': 'border-base-content/20',
    r'border-gray-300\b': 'border-base-content/30',
    r'border-gray-400\b': 'border-base-content/40',
    r'border-gray-500\b': 'border-base-content/50',
    r'border-gray-600\b': 'border-base-content/60',
    r'border-gray-700\b': 'border-base-content/70',
    r'border-gray-800\b': 'border-base-content/80',
    r'border-gray-900\b': 'border-base-content/90',
    
    # Common component patterns
    r'class="([^"]*)\bborder rounded-lg\b([^"]*)\"': r'class="\1border input-bordered rounded-lg\2"',
    r'class="([^"]*)\binput\b(?!-bordered)([^"]*)\"': r'class="\1input input-bordered\2"',
    r'class="([^"]*)\bbutton\b([^"]*)\"': r'class="\1btn\2"',
}

# Directories to search
SEARCH_DIRS = [
    'resources/views',
    'resources/js',
    'resources/css',
]

# File extensions to process
FILE_EXTENSIONS = ['.php', '.blade.php', '.js', '.jsx', '.vue', '.css']

def should_process_file(file_path):
    return any(file_path.endswith(ext) for ext in FILE_EXTENSIONS)

def process_file(file_path):
    try:
        with open(file_path, 'r', encoding='utf-8') as file:
            content = file.read()

        original_content = content
        modified = False

        for pattern, replacement in REPLACEMENTS.items():
            new_content = re.sub(pattern, replacement, content)
            if new_content != content:
                modified = True
                content = new_content

        if modified:
            # Create backup
            backup_path = f"{file_path}.bak"
            with open(backup_path, 'w', encoding='utf-8') as file:
                file.write(original_content)
            
            # Write modified content
            with open(file_path, 'w', encoding='utf-8') as file:
                file.write(content)
            
            print(f"✅ Modified: {file_path}")
            return True
        return False
    except Exception as e:
        print(f"❌ Error processing {file_path}: {str(e)}")
        return False

def main():
    modified_files = 0
    processed_files = 0
    
    for search_dir in SEARCH_DIRS:
        base_path = Path(search_dir)
        if not base_path.exists():
            print(f"Directory not found: {search_dir}")
            continue
            
        for root, _, files in os.walk(search_dir):
            for file in files:
                file_path = os.path.join(root, file)
                if should_process_file(file_path):
                    processed_files += 1
                    if process_file(file_path):
                        modified_files += 1

    print("\nConversion Summary:")
    print(f"Processed files: {processed_files}")
    print(f"Modified files: {modified_files}")
    print("\nBackup files have been created with .bak extension")
    print("Please review the changes before committing!")

if __name__ == "__main__":
    main()