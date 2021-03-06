<?php
// https://cheatsheetseries.owasp.org/cheatsheets/File_Upload_Cheat_Sheet.html
// Ensure that input validation is applied before validating the extensions.
// Only allow authorised users to upload files

// Determine where the php.ini to configure PHP configuration settings -> Set a file size limit
// echo php_ini_loaded_file()
// Implement strict server side validation on file size
// Server side restrictions to remove execute permission from uploaded files and upload folder
// Disable directory listing in the web server
// Limit number of file uploades or implement CAPTCHA to prevent DoS attack




// Implement strict server side validation on content of the file. Additionally file extension and content-type can also be validated
// Validate the file type, don't trust the Content-Type header as it can be spoofed
function check_file_type($file){
    $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime-type extension
    // echo finfo_file($finfo, $file);
    $fileType = finfo_file($finfo, $file);
    finfo_close($finfo);
    return $fileType;
}


// Change the filename to something generated by the application
// Renaming uploaded files avoids duplicate names in your upload destination, and also helps to prevent directory traversal attacks.

function new_file_name($fileName){
    $datetime = date("Y-m-d H:i:s");
    $ext = strtolower(substr($fileName, strripos($fileName, '.')+1));
    // Append datetime  
    $fileName = $datetime.'-'.$fileName;
    // Renaming entire file with SHA-256 and append last extension
    // Set a filename length limit. Restrict the allowed characters if possible -> Don't really need to set as we will use SHA-256 to restrict to 64 bits
    $newFileName = hash('sha256', $fileName).'.'.$ext;
    return $newFileName;    
}
?>