# PDF-Processing
PDF-Processing is a web interface for the callas pdfaPilot CLI. 
It is for the time being implemented only for test purposes.

## Prerequisits

* PHP 7
* Apache 2
* The callas software **pdfaPilot** has to be installed and added to the PATH. Additionally, the pdfaPilot licence file Licence.txt has to be copied to the same directory as the pdfaPilot binary, otherwise, it cannot be executed by www-data.

* The `upload_max_filesize` PHP setting should be increased (the default is only 2 MB), in connection, the `post_max_size` also must be increased. On the other hand, `max_file_uploads` can be set to only one, e.g. (In (/etc/php/7.0/apache2/)php.ini):

```
    ; Maximum allowed size for uploaded files.
    ; http://php.net/upload-max-filesize
    upload_max_filesize = 100M
    ; Maximum number of files that can be uploaded via a single request
    max_file_uploads = 1
	[...]
    ; Maximum size of POST data that PHP will accept.
    ; Its value may be 0 to disable the limit. It is ignored if POST data reading
    ; is disabled through enable_post_data_reading.
    ; http://php.net/post-max-size
    post_max_size = 120M
```
* To enable the pdfaPilot to embed MS Fonts in the PDF document, MS TrueType Fonts need to be installed:
```
    sudo apt-get install ttf-mscorefonts-installer
```

## Configuration

The main configuration file is `ini/config.ini` containing among other things paths to where uploaded and processed files are saved.
