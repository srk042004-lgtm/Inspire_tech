# Process all Ai_course PHP files
Get-ChildItem -Path "Ai_course\*.php" | ForEach-Object {
    $path = $_.FullName
    $content = Get-Content -Raw -LiteralPath $path
    $original = $content
    
    # Remove inline <style> blocks
    $content = [regex]::Replace($content, '(?s)\s*<style[^>]*>.*?</style>\s*', '')
    
    # Add style.css link if not present
    if ($content -notmatch 'href[^>]*\.\./style\.css') {
        $content = $content -replace '(</head>)', '    <link rel="stylesheet" href="../style.css">' + [Environment]::NewLine + '$1'
    }
    
    # Ensure body has ai-course-page class
    if ($content -notmatch '<body[^>]*class="[^"]*ai-course-page') {
        $content = $content -replace '<body>', '<body class="ai-course-page">'
        $content = $content -replace '<body\s+class="([^"]*)">', '<body class="ai-course-page $1">'
    }
    
    if ($content -ne $original) {
        Set-Content -LiteralPath $path -Value $content
        Write-Host "Updated: $($_.Name)"
    }
}

# Process teacher_login.php
if (Test-Path "teacher_login.php") {
    $content = Get-Content -Raw -LiteralPath "teacher_login.php"
    $original = $content
    
    # Remove inline<style> blocks
    $content = [regex]::Replace($content, '(?s)\s*<style[^>]*>.*?</style>\s*', '')
    
    # Add style.css link
    if ($content -notmatch 'href[^>]*style\.css') {
        $content = $content -replace '(</head>)', '    <link rel="stylesheet" href="style.css">' + [Environment]::NewLine + '$1'
    }
    
    if ($content -ne $original) {
        Set-Content -LiteralPath "teacher_login.php" -Value $content
        Write-Host "Updated: teacher_login.php"
    }
}

# Process Web Development course files
Get-ChildItem -Path "Web_development" -Include "*.php", "*.html" -Recurse | ForEach-Object {
    $path = $_.FullName
    $content = Get-Content -Raw -LiteralPath $path
    $original = $content
    
    # Remove inline <style> blocks
    $content = [regex]::Replace($content, '(?s)\s*<style[^>]*>.*?</style>\s*', '')
    
    # Calculate relative path to root style.css
    $relPath = $_.Directory.Name
    if ($relPath -eq "Html_and_Css" -or $relPath -eq "Javascript_class") {
        $styleLink = '../../style.css'
    } else {
        $styleLink = '../style.css'
    }
    
    # Add style.css link if not present
    if ($content -notmatch 'href[^>]*style\.css') {
        $content = $content -replace '(</head>)', '    <link rel="stylesheet" href="' + $styleLink + '">' + [Environment]::NewLine + '$1'
    }
    
    if ($content -ne $original) {
        Set-Content -LiteralPath $path -Value $content
        Write-Host "Updated: $($_.Name)"
    }
}

Write-Host "`nAll pages processed successfully!"
