# Save Uploaded Files

[![Build Status](https://travis-ci.org/aminyazdanpanah/save-uploaded-files.svg?branch=master)](https://travis-ci.org/aminyazdanpanah/save-uploaded-files)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/aminyazdanpanah/save-uploaded-files.svg?style=flat-square)](https://packagist.org/packages/aminyazdanpanah/save-uploaded-files)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/aminyazdanpanah/save-uploaded-files/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/aminyazdanpanah/save-uploaded-files/?branch=master)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/aminyazdanpanah/PHP-FFmpeg-video-streaming/blob/master/LICENSE)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/aminyazdanpanah/save-uploaded-files/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Total Downloads](https://img.shields.io/packagist/dt/aminyazdanpanah/save-uploaded-files.svg?style=flat-square)](https://packagist.org/packages/aminyazdanpanah/save-uploaded-files)


Handles multiple uploads with a powerful validation for an image, a video, an audio, and an archive files. This package allows you to handle your file after your file was uploaded. You can resize, convert, extract and so many things to do after uploading. At the end, you can extract details of files into an `array`.

## Features
* Easily manage your all uploaded files by using an `array`.
* Upload multiple files with one configuration
* validate your video, audio, image, archive files with different rules.
* Manage your files such as converting, resizing, extracting and so many things to do after your file was uploaded.
* Export your file information and insert them into a database. 
* Compatible with PHP >= 7.1.0.

## Installation

This version of the package is only compatible with PHP 7.1.0 or newer.

Install the package via composer:

``` bash
composer require aminyazdanpanah/save-uploaded-files
```

## Basic Usage

``` php
require_once 'vendor/autoload.php';

$export = function($filename){
	//Add additional validators and handle file after your file was uploaded.
    //...
}

$config_files = [
    [
        'name' => 'upload_key_name', //The key name that you send your file to the server
        'save_to' => $output_path, //The path you'd like to save your file(auto make new directory)
        'validator' => [
                'min_size' => 100, //Minimum size is 100KB
                'max_size' => 1024 * 2, //Maximum size is 2MB
                'allowed_extensions' => ['jpg', 'jpeg', 'png', ...] //Just images are allowed
        ],
        'export' => $export //Get a callback method(whatever you want to do with  your file after it was uploaded!)
    ],
    [
        ...
    ],
];

$uploads = uploads($config_files)->all();
```
**Important: Please see [example.php](https://github.com/aminyazdanpanah/save-uploaded-files/blob/master/examples/example.php) for more information. In that page you can see complete examples of usage this package.**

## Documentation
It is recommended to browse the source code as it is self-documented.

### Configuration

In the sever side you should create an array of config and pass it to `uploads()` method:
``` php
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
        ...
    ],
];

$uploads = Upload::files($config_files);
```

For more information about these attributes, please check the below tables:
##### Attributes of array of config

|     attr    	|  default  	|                         mean                         	|
|:-----------:	|:---------:	|:----------------------------------------------------:	|
|   name   	|     **mandatory:** must be specified      	|         The key name you send your file to the server-PHP($_Files).        	|
| save_to 	| **mandatory:** must be specified 	|          The path that you would like to save  your file in the server or computer.          	|
| save_as      | Base file name 	|          The name that you want to save.                     	|
|   override 	|     false     	|         Override original file with the same filename.         	|
|   validator  	|     no rules     	|         Validate your file before save(see validator table).         	|
|   export  	|     Nothing happens     	|         A callback method that you  specify additional rules and manage your file after it was uploaded.      	|
### Validation

#### Before Saving
You can validate your uploaded file before move the uploaded file to the destination. There are some rules that you can apply on your file: 
##### Attributes of validator

|     attr    	|  default  	|                         mean                         	|
|:-----------:	|:---------:	|:----------------------------------------------------:	|
|   min_size   	|     1      	|         The minimum size of files that are allowed (KiloByte).        	|
| max_size 	| 999999 	|          The maximum size of files that are allowed (KiloByte).          	|
| types      | '*' (everything) 	|          The types that are allowed (must be an array)                     	|

#### After Saving
You can set some rules after move the uploaded file to the destination by using a callback  method. To validate a file after saving, just check your file in the callback method and throw an `AYazdanpanah\SaveUploadedFiles\Exception\Exception`. In the end, put it in the array of config and pass it to `Upload::files($config)`. In the callback method, you can manage your file. For example, resize image or video, convert video, extracting an archive file and etc. After that, you are able to return your data.

``` php
$export = function ($filename) {
 
    //Add a  Validator: if $filename is not ...
    if (//condition 1) {
        throw new Exception("Exception 1");
    }
    
    //Add a  Validator: if $filename is not ...
    if (//condition 2) {
        throw new Exception("Exception 2");
    }
    ...
    
    //Handles file: resize image or video, convert video, extracting the archive and etc.
    ...
    
    // return whatever you want
    return $data;
}

$config = [
    [
        ... ,
        'export' => $export
    ],
    
Upload::files($config);
```

##### Validate and Manage a Video file
There are some factors that can use to validate a video. I recommended  [PHP-FFMpeg-video-streaming](https://github.com/aminyazdanpanah/PHP-FFmpeg-video-streaming) package that is a wrapper around [PHP-FFmpeg](https://github.com/PHP-FFMpeg/PHP-FFMpeg). You can validate your video by using its codec, duration, width, height, ratio, and other its attributes. After that, depends on whether your file is valid or not, you can throw an `Exception`. At the end, as it can be seen below, you can convert your video or whatever you want to do with your file and export your data:

``` php
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
        echo "$percentage % is transcoded\n";
    });

    //Delete the original file
    @unlink($filename);

    return $video_metadata->all();
};
```
For more information, please read [PHP FFMPEG Video Streaming documentation](https://github.com/aminyazdanpanah/PHP-FFmpeg-video-streaming) and [php-ffmpeg documentation](https://github.com/PHP-FFMpeg/PHP-FFMpeg#documentation).

##### Validate and Manage an image file
To validate and manage an image, I recommended  [php-image-resize](https://github.com/gumlet/php-image-resize) package. For more information I strongly recommand to read [php-image-resize documantation](https://gumlet.github.io/php-image-resize/class-Gumlet.ImageResize.html) . After that, depends on whether your file is valid or not, you can throw an `Exception`. At the end, you can export your data:

``` php
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
```

##### Validate and Manage an archive file
To validate and manage an archive file, I recommended using [UnifiedArchive](https://github.com/wapmorgan/UnifiedArchive) package. For more information I strongly recommand to read [UnifiedArchive documantation](https://github.com/wapmorgan/UnifiedArchive) . After that, depends on whether your file is valid or not, you can throw an `Exception`. At the end, you can export your data:

``` php
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
```

##### Validate and Manage an audio file
To validate and manage an archive file, I recommended using [php-ffmpeg](https://github.com/PHP-FFMpeg/PHP-FFMpeg) package. For more information I strongly recommand to read [php-ffmpeg](https://github.com/PHP-FFMpeg/PHP-FFMpeg) . After that, depends on whether your file is valid or not, you can throw an `Exception`. At the end, you can export your data:

``` php
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
```

### Exporting Data
After uploading, class return an object that is instance of `Filter`. You can export all details of your upload by using `all()` method:
```php
print_r($uploads->all());// Returns all uploads and their details.
```
##### Other possible methods

``` php
print_r($uploads->get(['upload_video', 'upload_raw']));// Returns specified uploads and their details.
print_r($uploads->except(['upload_raw', 'upload_image']));// Returns all uploads and their details except those ones specified.
print_r($uploads->first());// Returns first upload and it's detail.
print_r($uploads->succeeded());// Returns all succeeded uploads and their details.
print_r($uploads->failed());// Returns all failed uploads and their details.
print_r($uploads->names());// Returns all upload names.
print_r($uploads->count());// Returns the number of uploads
```

An example of details successful upload:

```text
[upload_doc] => Array
    (
        [status] => 1
        [message] => The file "CV" has been uploaded.
        [file_details] => Array
            (
                [name] => CV -  Work
                [type] => application/vnd.openxmlformats-officedocument.wordprocessingml.document
                [tmp_name] => phpE8E.tmp
                [size] => 27450
                [extension] => docx
                [basename] => CV.docx
                [save_as] => my_doc_name.docx
                [dir_path] => docs
                [file_path] => save-uploaded-files/examples/docs/my_doc_name.docx
                [upload_datetime] => 2019-02-16 23:25:43
                [stat] => Array
                    (
                        [0] => 2
                        [1] => 0
                        [2] => 33206
                        [3] => 1
                        [4] => 0
                        [5] => 0
                        [6] => 2
                        [7] => 27450
                        [8] => 1550355943
                        [9] => 1550355943
                        [10] => 1550355943
                        [11] => -1
                        [12] => -1
                        [dev] => 2
                        [ino] => 0
                        [mode] => 33206
                        [nlink] => 1
                        [uid] => 0
                        [gid] => 0
                        [rdev] => 2
                        [size] => 27450
                        [atime] => 1550355943
                        [mtime] => 1550355943
                        [ctime] => 1550355943
                        [blksize] => -1
                        [blocks] => -1
                    )

            )

    )
```


## Example
For see complete example, please go to [example.php](https://github.com/aminyazdanpanah/save-uploaded-files/blob/master/examples/example.php).

## Contributing

I'd love your help in improving, correcting, adding to the specification.
Please [file an issue](https://github.com/aminyazdanpanah/save-uploaded-files/issues)
or [submit a pull request](https://github.com/aminyazdanpanah/save-uploaded-files/pulls).

Please see [Contributing File](https://github.com/aminyazdanpanah/save-uploaded-files/blob/master/CONTRIBUTING.md) for more information.

## Security

If you discover a security vulnerability within this package, please send an e-mail to Amin Yazdanpanah via:
contact [AT] aminyazdanpanah • com.
## Credits

- [Amin Yazdanpanah](http://www.aminyazdanpanah.com/?u=github.com/aminyazdanpanah/save-uploaded-files)

## License

The MIT License (MIT). Please see [License File](https://github.com/aminyazdanpanah/save-uploaded-files/blob/master/LICENSE) for more information.
