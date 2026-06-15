Add-Type -AssemblyName System.IO.Compression.FileSystem;
$path = "d:\githubcloning\eSIR02Laravel\(SKRIPSI) BillyRahmansyah_15210008.docx"

if (-not (Test-Path $path)) {
    Write-Host "File not found: $path"
    exit 1
}

$zip = [System.IO.Compression.ZipFile]::OpenRead($path)
$doc = $zip.GetEntry('word/document.xml')
$stream = $doc.Open()
$reader = New-Object System.IO.StreamReader($stream)
$xmlString = $reader.ReadToEnd()
$reader.Close()
$stream.Close()
$zip.Dispose()

$xml = [xml]$xmlString
$ns = New-Object System.Xml.XmlNamespaceManager($xml.NameTable)
$ns.AddNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main')
$nodes = $xml.SelectNodes('//w:t', $ns)

$textBuilder = New-Object System.Text.StringBuilder
foreach ($n in $nodes) {
    [void]$textBuilder.AppendLine($n.InnerText)
}

$outputPath = "d:\githubcloning\eSIR02Laravel\skripsi_extracted.txt"
[System.IO.File]::WriteAllText($outputPath, $textBuilder.ToString())
Write-Host "Extracted text to $outputPath"
