<?php
$hasImagick = extension_loaded('imagick');
$hasGD = extension_loaded('gd');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Image Compressor (Batch + AJAX + Tailwind)</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

<div class="max-w-2xl mx-auto bg-white shadow p-8 rounded">
    <h1 class="text-2xl font-bold mb-4">Image Compressor</h1>

    <p class="mb-3 text-sm text-green-700">
        Available Library:
        <?php
            if ($hasImagick) echo "Imagick";
            elseif ($hasGD) echo "GD";
            else echo "None";
        ?>
    </p>

    <form id="uploadForm" class="space-y-4">
        <div>
            <label class="block mb-1 font-medium">Select Images (Batch Upload):</label>
            <input type="file" id="images" name="images[]" multiple class="border p-2 w-full bg-gray-50" required>
        </div>

        <div>
            <label class="block mb-1 font-medium">Quality (0-100):</label>
            <input type="number" id="quality" name="quality" value="80" min="0" max="100" class="border p-2 w-full">
        </div>

        <div>
            <label class="block mb-1 font-medium">Convert to WebP?</label>
            <select id="convert_webp" name="convert_webp" class="border p-2 w-full">
                <option value="yes">Yes</option>
                <option value="no">No (Keep Original Format)</option>
            </select>
        </div>

        <div>
            <label class="block mb-1 font-medium">Keep EXIF?</label>
            <select id="keep_exif" name="keep_exif" class="border p-2 w-full">
                <option value="no">Remove EXIF</option>
                <option value="yes">Keep EXIF</option>
            </select>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Start</button>
    </form>

    <div id="result" class="mt-6"></div>

</div>

<script>
document.getElementById('uploadForm').addEventListener('submit', function(e){
    e.preventDefault();

    const result = document.getElementById('result');
    result.innerHTML = '<p class="text-gray-600">Processing...</p>';

    const formData = new FormData();
    const files = document.getElementById('images').files;

    for (let i = 0; i < files.length; i++) {
        formData.append('images[]', files[i]);
    }

    formData.append('quality', document.getElementById('quality').value);
    formData.append('convert_webp', document.getElementById('convert_webp').value);
    formData.append('keep_exif', document.getElementById('keep_exif').value);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'process.php', true);

    xhr.onreadystatechange = function(){
        if(xhr.readyState === 4){
            result.innerHTML = xhr.responseText;
        }
    };

    xhr.send(formData);
});
</script>

</body>
</html>
