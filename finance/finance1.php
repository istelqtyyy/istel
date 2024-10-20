<?php
session_start(); // Start the session

include '../dbconnect.php';

// Initialize search term
$searchTerm = "";

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Folder Creation
    if (isset($_POST['folder_name'])) {
        $folderName = trim($_POST['folder_name']);
        if (!empty($folderName)) {
            // Check for duplicate folder name
            $stmt = $conn->prepare("SELECT * FROM folder WHERE folder_name = ?");
            $stmt->bind_param("s", $folderName);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) { // Only create folder if it doesn't exist
                $stmt = $conn->prepare("INSERT INTO folder (folder_name) VALUES (?)");
                $stmt->bind_param("s", $folderName);
                if ($stmt->execute()) {
                    $message = "Folder '$folderName' created successfully.";
                } else {
                    $message = "Error creating folder: " . $stmt->error;
                }
            } else {
                $message = "Folder '$folderName' already exists.";
            }
            $stmt->close();
        }
    }

    // Handle File Upload
    if (isset($_POST['upload'])) {
        $folderName = $_POST['folder_name'];
        foreach ($_FILES['documents']['name'] as $key => $name) {
            $targetFile = "uploads/" . basename($name);
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedFileTypes = ['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'];

            if (in_array($fileType, $allowedFileTypes) && move_uploaded_file($_FILES["documents"]["tmp_name"][$key], $targetFile)) {
                // Store file info in database
                $stmt = $conn->prepare("INSERT INTO files (folder_name, file_name) VALUES (?, ?)");
                $stmt->bind_param("ss", $folderName, $name);
                if ($stmt->execute()) {
                    $message = "File '$name' uploaded successfully."; // Show success message
                } else {
                    $message = "Error uploading file: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "Error uploading file: " . $name;
            }
        }
        // Redirect to refresh the page and show all folders and files
        header("Location: finance1.php");
        exit; // Important to exit after redirection
    }

    // Handle Folder Deletion
    if (isset($_POST['delete_folder'])) {
        $folderToDelete = $_POST['folder_name'];

        // First, get all files in the folder
        $stmt = $conn->prepare("SELECT file_name FROM files WHERE folder_name = ?");
        $stmt->bind_param("s", $folderToDelete);
        $stmt->execute();
        $result = $stmt->get_result();

        // Delete files from the server
        while ($row = $result->fetch_assoc()) {
            $fileName = $row['file_name'];
            unlink("uploads/" . $fileName); // Delete the actual file from the server
        }

        // Now, delete all files in the folder from the database
        $stmt = $conn->prepare("DELETE FROM files WHERE folder_name = ?");
        $stmt->bind_param("s", $folderToDelete);
        $stmt->execute(); // This deletes all files in the specified folder

        // Now, delete the folder from the folder table
        $stmt = $conn->prepare("DELETE FROM folder WHERE folder_name = ?");
        $stmt->bind_param("s", $folderToDelete);
        if ($stmt->execute()) {
            $message = "Folder '$folderToDelete' and its files deleted successfully.";
        } else {
            $message = "Error deleting folder: " . $stmt->error;
        }
        $stmt->close();

        // Redirect to refresh the page
        header("Location: finance1.php");
        exit;
    }



    // Handle File Deletion
    if (isset($_POST['delete_file'])) {
        $fileName = $_POST['delete'];
        $stmt = $conn->prepare("DELETE FROM files WHERE file_name = ?");
        $stmt->bind_param("s", $fileName);
        if ($stmt->execute()) {
            unlink("uploads/" . $fileName); // Delete the actual file
            $message = "File '$fileName' deleted successfully.";
        } else {
            $message = "Error deleting file: " . $stmt->error;
        }
        $stmt->close();
        // Redirect to refresh the page
        header("Location: finance1.php");
        exit;
    }


    // Handle Folder Renaming
    if (isset($_POST['rename_folder'])) {
        $oldFolderName = $_POST['old_folder_name'];
        $newFolderName = $_POST['new_folder_name'];
        $stmt = $conn->prepare("UPDATE folder SET folder_name = ? WHERE folder_name = ?");
        $stmt->bind_param("ss", $newFolderName, $oldFolderName);
        if ($stmt->execute()) {
            $message = "Folder '$oldFolderName' renamed to '$newFolderName' successfully.";
        } else {
            $message = "Error renaming folder: " . $stmt->error;
        }
        $stmt->close();
        // Redirect to refresh the page
        header("Location: finance1.php");
        exit;
    }

    // Handle File Renaming
    if (isset($_POST['rename_file'])) {
        $oldFileName = $_POST['old_file_name'];
        $newFileName = $_POST['new_file_name'];
        $oldFolderName = $_POST['folder_name'];
        if (rename("uploads/" . $oldFileName, "uploads/" . $newFileName)) {
            $stmt = $conn->prepare("UPDATE files SET file_name = ? WHERE file_name = ? AND folder_name = ?");
            $stmt->bind_param("sss", $newFileName, $oldFileName, $oldFolderName);
            if ($stmt->execute()) {
                $message = "File '$oldFileName' renamed to '$newFileName' successfully.";
            } else {
                $message = "Error renaming file: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Error renaming file in directory.";
        }
        // Redirect to refresh the page
        header("Location: finance1.php");
        exit;
    }

    // Handle Search Request
    if (isset($_POST['search'])) {
        $searchTerm = trim($_POST['search_term']);
    }
}

// Retrieve Folders
$folders = [];
$result = $conn->query("SELECT DISTINCT folder_name FROM folder"); // Get distinct folder names
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $folders[] = $row['folder_name']; // Store only the folder names
    }
}

// Filter folders by search term
$filteredFolders = array_filter($folders, function ($folder) use ($searchTerm) {
    return stripos($folder, $searchTerm) !== false; // Case insensitive search
});

// Retrieve folders that have files
$foldersWithFiles = [];
$filesInFolders = []; // Array to hold files in folders
foreach ($folders as $folder) {
    $stmt = $conn->prepare("SELECT file_name FROM files WHERE folder_name = ?");
    $stmt->bind_param("s", $folder);
    $stmt->execute();
    $filesResult = $stmt->get_result();
    if ($filesResult->num_rows > 0) {
        $foldersWithFiles[] = $folder; // Add to the list if there are files
        // Store files in an array
        while ($fileRow = $filesResult->fetch_assoc()) {
            $filesInFolders[$folder][] = $fileRow['file_name']; // Group files under their respective folders
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: black;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        h2 {
            margin-top: 10px;
            margin-bottom: 0px;
            color: #FFD700;
        }

        /* Home button */
        .home-button {
            position: absolute;
            top: 0px;
            left: 20px;
            background-color: #FFD700;
            border: none;
            border-radius: 5%;
            cursor: pointer;
            padding: 5px;
            font-size: 20px;
            color: black;
            transition: background-color 0.3s;
        }

        .home-button:hover {
            background-color: gray;
        }

        .container {
            display: flex;
            align-items: stretch;
            /* Align items to the top */
            gap: 20px;
            margin: 20px;
            min-height: 80vh;
            /* Set a minimum height */
            max-height: 70vh;
            /* Set a maximum height to prevent it from growing */
        }

        .section {
            flex: 1;
            /* Allow sections to stretch equally */
            background-color: #e6e6fa;
            /* Change the padding color */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(255, 255, 255, 0.1);
            text-align: center;
            border: 2px solid #2a283c;
            /* Add a border color */
            position: relative;
            /* Position relative to contain absolutely positioned children */
        }

        /* Styles specifically for the Uploaded Documents section */
        .uploaded-documents {
            max-height: 80vh;
            /* Set a specific max height for scrolling */
            overflow-y: auto;
            /* Enable vertical scrolling */
        }

        /* Additional styles for smooth transitions */
        .section h3,
        .section h4 {
            margin: 10px 0;
            /* Reduce the margin between headers and content */
        }

        .success,
        .error {
            color: #4CAF50;
        }

        .error {
            color: #ff4d4d;
        }

        input,
        select,
        button {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 140px;
        }

        button {
            background-color: #FFD700;
            color: black;
            cursor: pointer;
        }

        button:hover {
            background-color: gray;
        }

        /* Styles for Uploaded Documents Section */
        .file-cards {
            display: flex;
            flex-direction: column;
            gap: 10px;
            /* Space between each file card */
        }

        .file-card {
            background-color: #2a283c;
            /* Dark background for files */
            color: white;
            /* White text for better contrast */
            padding: 15px;
            border-radius: 8px;
            /* Rounded corners */
            display: flex;
            justify-content: space-between;
            /* Space out file name and actions */
            align-items: center;
            /* Center vertically */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            /* Add subtle shadow */
        }

        .file-name {
            font-weight: bold;
            /* Emphasize file name */
        }

        .file-actions {
            display: flex;
            flex-direction: column;
            /* Stack buttons vertically */
            align-items: flex-start;
            /* Align items to the start (left) */
            gap: 5px;
            /* Space between the buttons */
        }

        /* Ensure buttons have the same height */
        .btn-delete,
        .btn-rename {
            padding: 8px 12px;
            /* Adjust padding for uniform height */
            border: 1px solid #ccc;
            /* Optional: add border to maintain uniformity */
            border-radius: 5px;
            /* Rounded corners */
            background-color: #FFD700;
            /* Same background color */
            color: black;
            /* Text color */
            cursor: pointer;
            /* Pointer cursor on hover */
            transition: background-color 0.3s;
            /* Smooth background color transition */
            display: list-item;
        }

        .btn-delete:hover,
        .btn-rename:hover {
            background-color: gray;
            /* Same hover effect */
        }


        .rename-input {
            padding: 3px;
            /* Padding for input */
            border-radius: 5px;
            /* Rounded corners */
            border: 1px solid #ccc;
            /* Light border */
            margin-left: 1px;
            /* Space between input and button */
            width: 95px;
        }
    </style>
</head>

<body>
    <button class="home-button" onclick="window.location.href='../finance.php'">
        <i class="fas fa-home"></i>
    </button>
    <h2>Document Management</h2>
    <div class="container">
        <div class="section">
            <h3>Create New Folder</h3>
            <form method="post">
                <input type="text" name="folder_name" placeholder="Enter Folder Name" required>
                <button type="submit">Create Folder</button>
            </form>
            <h3>Delete Folder</h3>
            <form method="post">
                <input type="text" name="folder_name" placeholder="Folder Name to Delete" required>
                <button type="submit" name="delete_folder">Delete Folder</button>
            </form>
            <h3>Rename Folder</h3>
            <form method="post">
                <input type="text" name="old_folder_name" placeholder="Old Folder Name" required>
                <input type="text" name="new_folder_name" placeholder="New Folder Name" required>
                <button type="submit" name="rename_folder">Rename Folder</button>
            </form>
        </div>

        <div class="section">
            <h3>Upload Documents</h3>
            <form method="post" enctype="multipart/form-data">
                <input type="text" name="folder_name" placeholder="Enter Folder Name" required>
                <input type="file" name="documents[]" multiple required>
                <button type="submit" name="upload">Upload Files</button>
            </form>
        </div>

        <div class="section uploaded-documents">
            <h3>Uploaded Documents</h3>
            <?php if (isset($message)) : ?>
                <p class="<?php echo strpos($message, 'Error') === false ? 'success' : 'error'; ?>"><?php echo $message; ?></p>
            <?php endif; ?>

            <!-- Search Bar -->
            <form method="post">
                <input type="text" name="search_term" placeholder="Search for a folder..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" name="search">Search</button>
            </form>

            <h4>All Uploaded Folders</h4>
            <ul id="fileList">
                <?php foreach ($filteredFolders as $folder): ?>
                    <li onclick="toggleFiles('<?php echo htmlspecialchars($folder); ?>')" style="cursor:pointer;">
                        <strong style="color: #FFD700;"><?php echo htmlspecialchars($folder); ?></strong>
                    </li>
                    <ul id="files-<?php echo htmlspecialchars($folder); ?>" style="display: none;">
                        <?php if (isset($filesInFolders[$folder])): ?>
                            <div class="file-cards">
                                <?php foreach ($filesInFolders[$folder] as $file): ?>
                                    <li class="file-card">
                                        <div class="file-info">
                                            <a href="uploads/<?php echo htmlspecialchars($file); ?>" class="file-name" target="_blank"><?php echo htmlspecialchars($file); ?></a>
                                            <span class="file-actions">
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="delete" value="<?php echo htmlspecialchars($file); ?>">
                                                    <button type="submit" name="delete_file" class="btn-delete">Delete</button>
                                                </form>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="old_file_name" value="<?php echo htmlspecialchars($file); ?>">
                                                    <input type="hidden" name="folder_name" value="<?php echo htmlspecialchars($folder); ?>">
                                                    <input type="text" name="new_file_name" class="rename-input" placeholder="New File Name" required>
                                                    <button type="submit" name="rename_file" class="btn-rename">Rename</button>
                                                </form>
                                            </span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </ul>

                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
        function toggleFiles(folderName) {
            const fileList = document.getElementById(`files-${folderName}`);
            fileList.style.display = fileList.style.display === "none" ? "block" : "none";
        }
    </script>
</body>

</html>