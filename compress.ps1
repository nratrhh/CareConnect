Add-Type -AssemblyName System.Drawing
$srcEvents = "C:\xampp\htdocs\CARECONNECT\storage\app\public\events"
$srcProfiles = "C:\xampp\htdocs\CARECONNECT\storage\app\public\profiles"
$destDir = "C:\xampp\htdocs\CARECONNECT\optimized"
$destEvents = "$destDir\events"
$destProfiles = "$destDir\profiles"

New-Item -ItemType Directory -Force -Path $destEvents | Out-Null
New-Item -ItemType Directory -Force -Path $destProfiles | Out-Null

function Optimize-Image($src, $dest) {
    try {
        $img = [System.Drawing.Image]::FromFile($src)
        $newWidth = 800
        if ($img.Width -le $newWidth) {
            $newWidth = $img.Width
            $newHeight = $img.Height
        } else {
            $newHeight = [int](($img.Height / $img.Width) * $newWidth)
        }
        
        $bmp = New-Object System.Drawing.Bitmap($newWidth, $newHeight)
        $graph = [System.Drawing.Graphics]::FromImage($bmp)
        $graph.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
        $graph.DrawImage($img, 0, 0, $newWidth, $newHeight)
        
        $codec = [System.Drawing.Imaging.ImageCodecInfo]::GetImageDecoders() | Where-Object { $_.FormatID -eq [System.Drawing.Imaging.ImageFormat]::Jpeg.Guid }
        $encoderParams = New-Object System.Drawing.Imaging.EncoderParameters(1)
        $encoderParams.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter([System.Drawing.Imaging.Encoder]::Quality, [long]70)
        
        $bmp.Save($dest, $codec, $encoderParams)
        
        $graph.Dispose()
        $bmp.Dispose()
        $img.Dispose()
    } catch {
        Copy-Item $src -Destination $dest
    }
}

Write-Host "Compressing events..."
Get-ChildItem -Path $srcEvents -File | ForEach-Object { Optimize-Image $_.FullName "$destEvents\$($_.Name)" }
Write-Host "Compressing profiles..."
Get-ChildItem -Path $srcProfiles -File | ForEach-Object { Optimize-Image $_.FullName "$destProfiles\$($_.Name)" }

Write-Host "Zipping..."
Compress-Archive -Path "$destEvents", "$destProfiles" -DestinationPath "C:\xampp\htdocs\CARECONNECT\optimized_images.zip" -Force
Write-Host "Done!"
