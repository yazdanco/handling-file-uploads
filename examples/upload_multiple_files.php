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


//before require auto load, you should install package via composer:
//composer install
//or
//composer require aminyazdanpanah/handling-file-uploads
//Note: To find out how to install composer, just google "install or download composer"

require_once '../vendor/autoload.php';

use Gumlet\ImageResize;
use wapmorgan\UnifiedArchive\UnifiedArchive;
use AYazdanpanah\SaveUploadedFiles\Exception\Exception;

if(isset($_POST['submit'])) {

    $user_id = rand(10000, 1000000);

    //So, to export your uploaded file, create a callback method and use its callback to validate, update, modify, convert, copy and etc after your file was uploaded.
    /**
     * Add additional validators and handle image after your file was uploaded.
     *
     * @param $filename
     * @return ImageResize
     */
    $image_path = __DIR__ . "/images/user/$user_id";
    $export_image = function ($filename) use ($image_path) {
        //Add a  Validator: check if the file is image
        if (!is_type("image", $filename)) {
            throw new Exception("Your file is not an image!");
        }

        $image_metadata = exif_read_data($filename);

        //Add a  Validator: check whether the image is square or not
        if ($image_metadata['COMPUTED']['Width'] / $image_metadata['COMPUTED']['Height'] != 1) {
            throw new Exception("Your image must be square!");
        }

        if (!is_dir($image_path . "/thumbnail")) {
            mkdir($image_path . "/thumbnail", 0777, true);
        }

        // Resize and crop your image
        $image = new ImageResize($filename);
        $image->resizeToWidth(50)->save($image_path . "/thumbnail/thumb_50.jpg");
        $image->resizeToWidth(100)->save($image_path . "/thumbnail/thumb_100.jpg");
        $image->resizeToWidth(240)->save($image_path . "/thumbnail/thumb_240.jpg");
        $image->resizeToBestFit(500, 300)->save($image_path . "/thumbnail/thumb_500_300.jpg");
        $image->crop(200, 200)->save($image_path . "/thumbnail/thumb_crop_200_200.jpg");

        return $image_metadata;
    };

    /**
     * Add additional validators and handle video after your file was uploaded.
     *
     * @param $filename
     * @return array
     */
    $video_path = __DIR__ . "/videos/$user_id";
    $video_name = str_random();
    $export_video = function ($filename) use ($video_path, $video_name) {

        $video = AYazdanpanah\FFMpegStreaming\FFMpeg::create()
            ->open($filename);

        //Extracting video meta data
        $video_metadata = $video->getFirstStream();

        //Add a  Validator: check if the file is video and video duration is not longer than 1 minute
        if (!$video_metadata->isVideo() && null === ($duration = $video_metadata->get('duration')) && $duration >= 60) {
            throw new Exception("Your file is not a video or your video duration is longer than 1 minute!");
        }

        //Add a  Validator: check if the video is HD or higher resolution
        if ($video_metadata->get('width',1) * $video_metadata->get('height', 1) < 1280 * 720) {
            throw new Exception("Sorry, your video must be at least HD or higher resolution");
        }

        //Add a  Validator: check if the video ratio is 16 / 9
        if ($video_metadata->getDimensions()->getRatio()->getValue() == 16 / 9) {
            throw new Exception("Sorry, the video ratio must be 16 / 9");
        }

        //Extracting image and resize it
        $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(intval($duration / 4)))->save("$video_path/screenshots.jpg");
        $image = new ImageResize("$video_path/screenshots.jpg");
        $image->resizeToWidth(240)->save("$video_path/{$video_name}_screenshots_small.jpg");

        //Extracting gif
        $video->gif(FFMpeg\Coordinate\TimeCode::fromSeconds(3), new FFMpeg\Coordinate\Dimension(240, 95), 3)
            ->save("$video_path/{$video_name}_animation.gif");

        //Create the dash files(it is better to create a job and dispatch it-do it in the background)
        mkdir("$video_path/dash/$video_name", 0777, true);
        dash($filename, "$video_path/dash/$video_name/output.mpd", function ($audio, $format, $percentage) {
            echo "$percentage % transcoded\n";
        });

        //Delete the original file
        @unlink($filename);

        return $video_metadata->all();
    };

    /**
     * Add additional validators and handle archive after your file was uploaded.
     *
     * @param $filename
     * @return array
     */
    $archive_path = __DIR__ . "/archive/$user_id";
    $archive_name = str_random();
    $archive_export = function ($filename) use ($archive_path, $archive_name) {
        mkdir("$archive_path/$archive_name");
        $archive = UnifiedArchive::open($filename);

        //Add a  Validator: check whether the file is able to open or not
        if (null === $archive) {
            unlink($filename);
            throw new Exception("Sorry!we could not open the archive.
             Please check whether your extension has been installed or not.
             Your file may be corrupted or is encrypted");
        }

        //Add a  Validator: check whether the profile.jpg is in archive or not
        if (!$archive->isFileExists('profile.jpg')) {
            unlink($filename);
            throw new Exception("Sorry!we could not find 'profile.jpg' in the archive");
        }

        //Extracting files
        $archive->extractFiles("$archive_path/$archive_name");

        return $archive->getFileNames();
    };

    /**
     * Add additional validators and handle document after your file was uploaded.
     *
     * @param $filename
     * @return array
     */
    $doc_path = __DIR__ . "/docs/$user_id";
    $doc_name = str_random();
    $doc_export = function ($filename) use ($doc_path, $doc_name) {
        mkdir($doc_path . '/backup');
        copy($filename, "$doc_path/backup/$doc_name.backup");
        return ['backup_to' => $doc_path . '/backup'];
    };

    /**
     * Add additional validators and handle audio after your file was uploaded.
     *
     * @param $filename
     * @return array
     */
    $audio_path = __DIR__ . "/audios/$user_id";
    $audio_name = str_random();
    $audio_export = function ($filename) use ($audio_path, $audio_name) {
        mkdir($audio_path . '/flac');
        $audio = AYazdanpanah\FFMpegStreaming\FFMpeg::create()
            ->open($filename);
        $audio_metadata = $audio->getFirstStream();

        //Add a  Validator: check if the file is audio
        if (!$audio_metadata->isAudio() && null === ($duration = $audio_metadata->get('duration')) && $duration <= 60) {
            throw new Exception("Sorry, your file is not an audio or your audio duration must be longer than 1 minute!");
        }

        //Add a  Validator: check if the file is mp3
        if (!strstr($audio_metadata->get('codec_name'), 'mp3')) {
            throw new Exception("Sorry, your audio format must be mp3!");
        }

        //Convert the file into flac format
        $format = new FFMpeg\Format\Audio\Flac();

        $format->on('progress', function ($audio, $format, $percentage) {
            echo "$percentage % transcoded\n";
        });

        $format
            ->setAudioChannels(2)
            ->setAudioKiloBitrate(256);

        $audio->save($format, "$audio_path/flac/$audio_name.flac");

        return $audio_metadata->all();
    };

    $config_files = [
        [
            'name' => 'upload_image', //The key name that you send your file to the server
            'save_to' => $image_path, //The path you'd like to save your file(auto make new directory)
            'validator' => [
                'min_size' => 100, //Minimum size is 100KB
                'max_size' => 1024 * 2, //Maximum size is 2MB
                'allowed_extensions' => ['jpg', 'jpeg', 'png'] //Just images are allowed
            ],
            'export' => $export_image //Get a callback method(what happen to file after uploading!)
        ],
        [
            'name' => 'upload_archive', //The key name that you send to the server
            'save_to' => $archive_path, //The path you'd like to save your file
            'save_as' => $archive_name, //The name you'd like to save in your server
            'validator' => [
                'min_size' => 10, //Minimum size is 10KB
                'max_size' => 1024, //Maximum size is 1MB
                'allowed_extensions' => ['zip', '7z', 'rar', 'gz', 'bz2', 'xz', 'cab', 'tar', 'tar.gz', 'tar.bz2', 'tar.x', 'tar.Z', 'iso'] //Just archive files are allowed
            ],
            'export' => $archive_export //Get a callback method(what happen to file after uploading!)
        ],
        [
            'name' => 'upload_doc', //The key name that you send to the server
            'save_to' => $doc_path, //The path you'd like to save your file
            'save_as' => $doc_name, //The name you'd like to save in your server
            'override' => true, //Replace the file in the destination
            'validator' => [
                'min_size' => 10, //Minimum size is 10KB
                'max_size' => 1024, //Maximum size is 1MB
                'allowed_extensions' => ['doc', 'docx'] //Just doc and docx are allowed
            ],
            'export' => $doc_export
        ],
        [
            'name' => 'upload_audio', //The key name that you send to the server
            'save_to' => $audio_path, //The path you'd like to save your file
            'save_as' => $audio_name, //The name you'd like to save in your server
            'override' => true, //Replace the file in the destination
            'validator' => [
                'min_size' => 100, //Minimum size is 100KB
                'max_size' => 1024 * 10, //Maximum size is 10MB
                'allowed_extensions' => ['mp3', 'aac', 'ogg'] //Just audio files are allowed
            ],
            'export' => $audio_export
        ],
        [
            'name' => 'upload_video', //The key name that you send to the server
            'save_to' => $video_path, //The path you'd like to save your file
            'save_as' => $video_name, //The name you'd like to save in your server(generate random name)
            'validator' => [
                'min_size' => 512, //Minimum size is 512KB
                'max_size' => 1024 * 170, //Maximum size is 170MB
                'allowed_extensions' => video_types() //Just video files are allowed
            ],
            'export' => $export_video
        ],
        [
            'name' => 'upload_raw', //The key name that you send to the server
            'save_to' => __DIR__ . '/raw' //The path you'd like to save your file
        ]
    ];

    $uploads = uploads($config_files);

    echo "<pre>";

    echo "
    |------------------------------------------------------All uploads--------------------------------------------------------------|
    ";
    print_r($uploads->all()); //Returns all uploads and their details.
    echo "
    |-------------------------------------------------------Get some uploads-------------------------------------------------------------|
    ";
    print_r($uploads->get(['upload_video', 'upload_raw'])); //Returns specified uploads and their details.
    echo "
    |--------------------------------------------------------Except some uploads------------------------------------------------------------|
    ";
    print_r($uploads->except(['upload_raw', 'upload_image'])); //Returns all uploads and their details except those ones specified.
    echo "
    |--------------------------------------------------------First upload------------------------------------------------------------|
    ";
    print_r($uploads->first()); //Returns first upload and it's detail.
    echo "
    |--------------------------------------------------------All succeeded uploads------------------------------------------------------------|
    ";
    print_r($uploads->succeeded()); //Returns all succeeded uploads and their details.
    echo "
    |--------------------------------------------------------All failed uploads------------------------------------------------------------|
    ";
    print_r($uploads->failed()); //Returns all failed uploads and their details.
    echo "
    |--------------------------------------------------------All names of uploads------------------------------------------------------------|
    ";
    print_r($uploads->names()); //Returns all upload names.
    echo "
    |--------------------------------------------------------The number of uploads------------------------------------------------------------|
    ";
    print_r($uploads->count()); //Returns the number of uploads

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
    Select an audio: <input type="file" name="upload_audio" id="upload_audio"><br><br>
    Select an archive: <input type="file" name="upload_archive" id="upload_archive"><br><br>
    Select a document(word): <input type="file" name="upload_doc" id="upload_doc"><br><br>
    Select a raw file: <input type="file" name="upload_raw" id="upload_raw"><br><br>
    <input type="submit" value="Upload" name="submit"><br><br>
</form>
************************************************************************************************************************

</body>
</html>