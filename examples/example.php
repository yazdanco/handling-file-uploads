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
// composer install aminyazdanpanah/save-uploaded-files
// Note: to find out how to install composer, just google "install or download composer"
require_once '../vendor/autoload.php';

if(isset($_POST["submit"])) {
    $config_files = [
        [
            'name' => 'upload_image',
            'save_to' => __DIR__ . "/images/user/" . time(),
            'validator' => [
                'min_size' => 100,
                'max_size' => 2048,
                'types' => ['jpg', 'jpeg', 'png']
            ]
        ],
        [
            'name' => 'upload_doc',
            'save_to' => __DIR__ . "/docs",
            'save_as' => 'my_doc_name',
            'override' => true,
            'validator' => [
                'min_size' => 10,
                'max_size' => 1024,
                'types' => ['doc', 'docx']
            ]
        ],
        [
            'name' => 'upload_video',
            'save_to' => __DIR__ . "/videos",
            'save_as' => generateRandomString(),
            'validator' => [
                'min_size' => 512,
                'max_size' => 1024 * 70,
                'types' => video_types()
            ]
        ],
        [
            'name' => 'upload_raw',
            'save_to' => __DIR__ . "/raw"
        ]
    ];
    echo "<pre>";
    print_r(save_as($config_files));
    echo "</pre>";
}
?>
<!DOCTYPE html>
<html>
<body>

<form action="" method="post" enctype="multipart/form-data">
    Select files to upload: <br><br><br>
    Select an image: <input type="file" name="upload_image" id="upload_image"><br><br>
    Select a video: <input type="file" name="upload_video" id="upload_video"><br><br>
    Select a document(word): <input type="file" name="upload_doc" id="upload_doc"><br><br>
    Select a raw file: <input type="file" name="upload_raw" id="upload_raw"><br><br>
    <input type="submit" value="Upload" name="submit"><br><br>
</form>

</body>
</html>
