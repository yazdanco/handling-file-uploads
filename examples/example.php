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


//
require_once '../vendor/autoload.php';

if(isset($_POST["submit"])) {
    $files = [
        [
            'name' => 'upload_image',
            'save_to' => __DIR__ . "/images",
            'validator' => [
                'min_size' => 512,
                'max_size' => 2048,
                'type' => ['jpg', 'jpeg', 'png']
            ]
        ],
        [
            'name' => 'upload_doc',
            'save_to' => __DIR__ . "/docs",
            'validator' => [
                'min_size' => 30,
                'max_size' => 1024,
                'type' => ['doc', 'docx']
            ]
        ],
        [
            'name' => 'upload_raw',
            'save_to' => __DIR__ . "/raw"
        ]
    ];
    echo "<pre>";
    print_r(save_as($files));
    echo "</pre>";
}
?>
<!DOCTYPE html>
<html>
<body>

<form action="" method="post" enctype="multipart/form-data">
    Select files to upload: <br><br><br>
    Select an image: <input type="file" name="upload_image" id="upload_image"><br><br>
    Select an document(word): <input type="file" name="upload_doc" id="upload_doc"><br><br>
    Select an raw: <input type="file" name="upload_raw" id="upload_raw"><br><br>
    <input type="submit" value="Upload" name="submit"><br><br>
</form>

</body>
</html>
