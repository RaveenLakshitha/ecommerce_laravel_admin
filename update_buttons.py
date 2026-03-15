import os
import re

search_dir = r"e:\Program Files\xampp\htdocs\test\appointmentsystem\docappointment - Copy\resources\views"
files_to_check = []

for root, dirs, files in os.walk(search_dir):
    for filename in files:
        if filename == 'create.blade.php':
            files_to_check.append(os.path.join(root, filename))

submit_class = "inline-flex items-center justify-center px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200 transition-colors duration-200 shadow-sm"
cancel_class = "inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 dark:bg-transparent dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors duration-200 shadow-sm"

for filepath in files_to_check:
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    def replace_action_buttons(match):
        block = match.group(0)
        
        # Replace submit button class
        block = re.sub(
            r'(<button\s+type="submit"[^>]*?class=")[^"]+(")',
            rf'\g<1>{submit_class}\g<2>',
            block,
            flags=re.IGNORECASE
        )
        
        # Replace cancel link class
        block = re.sub(
            r'(<a\s+href="\{\{\s*route\([^}]+\)\s*\}\}"[^>]*?class=")[^"]+(")',
            rf'\g<1>{cancel_class}\g<2>',
            block,
            flags=re.IGNORECASE
        )
        
        return block

    # We match the <div> that has "flex flex-col sm:flex-row" and contains the buttons
    # The div ends with </div> and usually comes right before </form>
    new_content = re.sub(
        r'<div\s+class="flex\s+flex-col\s+sm:flex-row[^>]*>.*?</a>\s*</div>',
        replace_action_buttons,
        content,
        flags=re.DOTALL | re.IGNORECASE
    )

    if new_content != content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Updated {filepath}")
    else:
        print(f"No changes in {filepath}")
