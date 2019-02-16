<?php

/**
 * Copyright 2019 Amin Yazdanpanah<http://www.aminyazdanpanah.com>.
 *
 * Licensed under the MIT License;
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      https://opensource.org/licenses/MIT
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


// before require auto load, you should install package via composer:
// composer install
//or
// composer require aminyazdanpanah/save-uploaded-files
// Note: to find out how to install composer, just google "install or download composer"
require_once '../vendor/autoload.php';

if(isset($_POST["submit"])) {
    $config_files = [
        [
            'name' => 'upload_image',// The key name that you send to the server
            'save_to' => __DIR__ . "/images/user/" . time(),// The path you'd like to save your file(auto make new directory)
            'validator' => [
                'min_size' => 100,// Minimum size is 100KB
                'max_size' => 1024 * 2,// Maximum size is 2MB
                'types' => ['jpg', 'jpeg', 'png']// Just images are allowed
            ]
        ],
        [
            'name' => 'upload_doc',// The key name that you send to the server
            'save_to' => __DIR__ . "/docs",// The path you'd like to save your file
            'save_as' => 'my_doc_name',// The name you'd like to save in your server
            'override' => true,// Replace the file in the destination
            'validator' => [
                'min_size' => 10,// Minimum size is 10KB
                'max_size' => 1024,// Maximum size is 1MB
                'types' => ['doc', 'docx']// Just doc and docx are allowed
            ]
        ],
        [
            'name' => 'upload_video',// The key name that you send to the server
            'save_to' => __DIR__ . "/videos",// The path you'd like to save your file
            'save_as' => generateRandomString(),// The name you'd like to save in your server(generate random name)
            'validator' => [
                'min_size' => 512,// Minimum size is 512KB
                'max_size' => 1024 * 170,// Maximum size is 170MB
                'types' => video_types()// Just video files are allowed
            ]
        ],
        [
            'name' => 'upload_raw',// The key name that you send to the server
            'save_to' => __DIR__ . "/raw"// The path you'd like to save your file
        ]
    ];

    $save_as = save_as($config_files);

    echo "<pre>";

    echo "
    |------------------------------------------------------All uploads--------------------------------------------------------------|
    ";
    print_r($save_as->all());// Returns all uploads and their details.
    echo "
    |-------------------------------------------------------Get some uploads-------------------------------------------------------------|
    ";
    print_r($save_as->get(['upload_video', 'upload_raw']));// Returns specified uploads and their details.
    echo "
    |--------------------------------------------------------Except some uploads------------------------------------------------------------|
    ";
    print_r($save_as->except(['upload_raw', 'upload_image']));// Returns all uploads and their details except those ones specified.
    echo "
    |--------------------------------------------------------First upload------------------------------------------------------------|
    ";
    print_r($save_as->first());// Returns first upload and it's detail.
    echo "
    |--------------------------------------------------------All succeeded uploads------------------------------------------------------------|
    ";
    print_r($save_as->succeeded());// Returns all succeeded uploads and their details.
    echo "
    |--------------------------------------------------------All failed uploads------------------------------------------------------------|
    ";
    print_r($save_as->failed());// Returns all failed uploads and their details.
    echo "
    |--------------------------------------------------------All names of uploads------------------------------------------------------------|
    ";
    print_r($save_as->names());// Returns all upload names.
    echo "
    |--------------------------------------------------------The number of uploads------------------------------------------------------------|
    ";
    print_r($save_as->count());// Returns the number of uploads

    echo "</pre>";
}
?>
<!DOCTYPE html>
<html>
<body>
************************************************************************************************************************
<form action="" method="post" enctype="multipart/form-data">
    Select files to upload: <br><br><br>
    Select an image: <input type="file" name="upload_image" id="upload_image"><br><br>
    Select a video: <input type="file" name="upload_video" id="upload_video"><br><br>
    Select a document(word): <input type="file" name="upload_doc" id="upload_doc"><br><br>
    Select a raw file: <input type="file" name="upload_raw" id="upload_raw"><br><br>
    <input type="submit" value="Upload" name="submit"><br><br>
</form>
************************************************************************************************************************

</body>
</html>
