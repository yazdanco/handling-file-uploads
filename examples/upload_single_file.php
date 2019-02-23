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


require_once '../vendor/autoload.php';

use AYazdanpanah\SaveUploadedFiles\File;
use AYazdanpanah\SaveUploadedFiles\Validator;
use Gumlet\ImageResize;

if(isset($_POST['submit'])) {
    $image_path = __DIR__ . "/images/user/123456";
    $export_image = function ($filename) use ($image_path) {
        //Add a  Validator: check if the file is image
        if (!is_type("image", $filename)) {
            throw new Exception("Your file is not an image!");
        }

        // Resize and crop your image
        mkdir($image_path . "/thumbnail", 0777, true);
        $image = new ImageResize($filename);
        $image->resizeToWidth(50)->save($image_path . "/thumbnail/thumb_50.jpg");

        return exif_read_data($filename);
    };

    $validator = new Validator();

    $validator = $validator->setMinSize(100)
        ->setMaxSize(1024 * 3)
        ->setType(['jpg', 'png', 'jpeg']);

    $upload = new File();

    $upload = $upload->file('upload_image')
        ->setValidator($validator)
        ->setOverride(false)
        ->setSaveAs('my_image')
        ->save($image_path, $export_image);

    var_dump("<pre>", $upload, "</pre>");
}
?>

<!DOCTYPE html>
<html>
<body>
************************************************************************************************************************
<form action="" method="post" enctype="multipart/form-data">
    Select an image: <input type="file" name="upload_image" id="upload_image"><br>
    <input type="submit" value="Upload" name="submit">
</form>
************************************************************************************************************************

</body>
</html>