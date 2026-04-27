import os

def export_files_with_content(root_path, output_file="exported_files.txt"):
    """
    Recursively scans a folder and writes:
    - file path
    - file content
    into a single text file
    """

    # File extensions usually considered binary
    binary_extensions = {
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".exe", ".dll", ".pdf", ".zip", ".rar",
        ".mp4", ".mp3", ".ico", ".class"
    }

    with open(output_file, "w", encoding="utf-8") as output:
        for folder_name, subfolders, file_names in os.walk(root_path):
            for file_name in file_names:
                full_path = os.path.join(folder_name, file_name)
                extension = os.path.splitext(file_name)[1].lower()

                # Skip binary files
                if extension in binary_extensions:
                    output.write(f"\n{'='*80}\n")
                    output.write(f"FILE: {full_path}\n")
                    output.write("Skipped (binary file)\n")
                    continue

                output.write(f"\n{'='*80}\n")
                output.write(f"FILE: {full_path}\n")
                output.write(f"{'='*80}\n")

                try:
                    with open(full_path, "r", encoding="utf-8") as file:
                        content = file.read()
                        output.write(content)
                        output.write("\n")

                except Exception as error:
                    output.write(f"Could not read file: {error}\n")

    print(f"Export completed → {output_file}")


# Take folder path input from user
folder_path = input("Enter folder path: ").strip()

if os.path.exists(folder_path):
    export_files_with_content(folder_path)
else:
    print("Invalid path.")