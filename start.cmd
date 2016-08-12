rem @echo off
echo Starting servers...
set PHP_FCGI_MAX_REQUESTS=0
set SRVPATH=C:\nginx
start /D%SRVPATH% nginx.exe
%SRVPATH%\RunHiddenConsole.exe %SRVPATH%\php\php-cgi.exe -b 127.0.0.1:9000 -c %SRVPATH%/php/php.ini
%SRVPATH%\RunHiddenConsole.exe %SRVPATH%\php\php-cgi.exe -b 127.0.0.1:9001 -c %SRVPATH%/php/php.ini
