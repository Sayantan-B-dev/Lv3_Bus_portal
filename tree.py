import os

# Entire folders/files to ignore
IGNORE_NAMES = {
    ".git",
    ".github",
    ".gitignore",
    ".gitattributes",

    "node_modules",
    "vendor",              # composer dependencies
    "__pycache__",
    ".pytest_cache",
    ".venv",
    "venv",

    ".vscode",
    ".idea",

    ".DS_Store",
    "Thumbs.db",

    "package-lock.json",
    "yarn.lock",
    "pnpm-lock.yaml",
    "composer.lock",

    ".env",
    ".env.local",
    ".env.production",

    "storage",             # logs/cache/session junk
    "logs",
    "cache",

    "folder_tree.txt",
    "tree.py"
}

# File extensions to ignore
IGNORE_EXTENSIONS = {
    ".log",
    ".tmp",
    ".cache"
}


def should_ignore(item_name, full_path):
    """
    Decide whether file/folder should be ignored.
    """

    if item_name in IGNORE_NAMES:
        return True

    _, extension = os.path.splitext(item_name)

    if extension in IGNORE_EXTENSIONS:
        return True

    # Ignore hidden files/folders starting with dot
    if item_name.startswith("."):
        return True

    return False


def generate_tree(current_path, prefix=""):
    tree_output = []

    try:
        items = sorted([
            item for item in os.listdir(current_path)
            if not should_ignore(
                item,
                os.path.join(current_path, item)
            )
        ])
    except PermissionError:
        return [prefix + "└── [Permission Denied]"]

    for index, item in enumerate(items):
        full_path = os.path.join(current_path, item)

        is_last = index == len(items) - 1
        branch = "└── " if is_last else "├── "

        tree_output.append(prefix + branch + item)

        if os.path.isdir(full_path):
            next_prefix = "    " if is_last else "│   "
            tree_output.extend(
                generate_tree(full_path, prefix + next_prefix)
            )

    return tree_output


def main():
    root_path = os.getcwd()
    root_name = os.path.basename(root_path)

    output = [root_name]
    output.extend(generate_tree(root_path))

    final_output = "\n".join(output)

    print(final_output)

    with open("folder_tree.txt", "w", encoding="utf-8") as f:
        f.write(final_output)

    print("\nClean tree exported to folder_tree.txt")


if __name__ == "__main__":
    main()