## PDF-Processing
PDF-Processing is a web interface for the callas pdfaPilot CLI. It allows a logged in user to upload a PDF file, perform validation of and conversion to PDF/A and to download a converted file. 

The software is posted here as it is installed at the Technical University of Berlin. If you wish to use it, you will probably want to adapt images, links and some texts to your needs. All links and texts are found in `ini/messages_<lang>.ini`. Colors can be adjusted in `css/pdf.css`.  

The current version presupposes a shibboleth login configuration in the Apache server.

For a version supporting LDAP see the tag 1.0_ldap. That version however has no help page and no language selector. To get those features using LDAP, you have to do some handiwork.

### Prerequisits

* PHP 7
* Apache 2
* The callas software **pdfaPilot** has to be installed and added to the PATH. Additionally, the pdfaPilot licence file Licence.txt has to be copied to the same directory as the pdfaPilot binary, otherwise, it cannot be executed by www-data.

* The `upload_max_filesize` PHP setting should be increased (the default is only 2 MB), in connection, the `post_max_size` also must be increased. On the other hand, `max_file_uploads` can be set to only one, e.g. (In (/etc/php/7.0/apache2/)php.ini):

```
    ; Maximum allowed size for uploaded files.
    ; http://php.net/upload-max-filesize
    upload_max_filesize = 120M
    ; Maximum number of files that can be uploaded via a single request
    max_file_uploads = 1
	[...]
    ; Maximum size of POST data that PHP will accept.
    ; Its value may be 0 to disable the limit. It is ignored if POST data reading
    ; is disabled through enable_post_data_reading.
    ; http://php.net/post-max-size
    post_max_size = 121M
```
* If you use a linux server, you need to install MS TrueType Fonts to enable the pdfaPilot to embed MS Fonts in the PDF document:
```
    sudo apt-get install ttf-mscorefonts-installer
```

* You may also want a cronjob deleting older uploaded and converted files, something linke this:
```
30 23 * * * www-data find /usr/local/pdfa_conversion/* -mtime +1 -exec rm {} \; >/dev/null 2>&1
```

### Configuration

The main configuration file is `ini/config.ini` containing among other things paths to where uploaded and processed files are saved.

All messages (including the help page) are stored in english and german in `ini/messages_<lang>.ini`

