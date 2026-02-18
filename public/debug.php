<?php
echo "OK<br>";
echo "PHP: " . phpversion() . "<br>";
echo "Manifest: " . (file_exists(__DIR__ . '/build/manifest.json') ? 'YES' : 'NO') . "<br>";
echo "Hot: " . (file_exists(__DIR__ . '/hot') ? 'YES' : 'NO') . "<br>";
echo "CSS: " . (file_exists(__DIR__ . '/build/assets/app-45623770.css') ? 'YES' : 'NO') . "<br>";
echo "JS: " . (file_exists(__DIR__ . '/build/assets/app-4f44e334.js') ? 'YES' : 'NO') . "<br>";
