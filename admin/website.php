<?php
require_once __DIR__ . '/../includes/functions.php';

// Pastikan hanya admin yang sudah login dapat membuka pratinjau website.
require_admin();

// Buka website publik dengan tampilan pelanggan dan identitas Admin.
// Parameter URL menjaga pratinjau tetap khusus pada tab yang dibuka admin.
redirect('../index.php?admin_preview=1');
