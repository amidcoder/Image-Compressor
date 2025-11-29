# Image Compressor (Batch + AJAX + Tailwind)

A fully AJAX-enabled, batch image compression tool built with PHP.  
It supports both **Imagick** and **GD**, automatically selecting the best available library.

## Features
- Batch image upload (multiple files at once)
- AJAX-powered processing (no reloads)
- User-defined compression quality
- Optional WebP conversion
- Optional keep/remove EXIF metadata
- Automatic library detection (Imagick → GD fallback)
- TailwindCSS UI
- Outputs compressed files with direct download links

## Requirements
- PHP 7.4+
- Imagick or GD extension
- Writable `uploads/` and `output/` directories

## Installation
1. Clone the repository:
```bash
   git clone https://github.com/yourusername/image-compressor.git
```
2. Make sure directories uploads/ and output/ are writable.
3. Open index.php in your browser.

## Upload
1. Upload multiple JPEG/PNG images
2. Set compression quality (0–100)
3. Choose to convert to WebP or keep original format
4. Decide whether to keep or strip EXIF metadata
5. Get instant output links for all processed images

## Project Structure
image-compressor/
├── index.php
├── process.php
├── uploads/
├── output/
└── README.md

## License
MIT
