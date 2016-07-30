Set-Location "$env:USERPROFILE\Documents\GitHub\RESTTutorial03\src"

start-job {
    Start-Sleep -Seconds 1
    Start-Process 'http://127.0.0.1:8000/hello/world'
    }
    
php.exe -S 127.0.0.1:8000 -t public public/index.php