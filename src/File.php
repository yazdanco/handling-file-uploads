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

namespace AYazdanpanah\SaveUploadedFiles;


use AYazdanpanah\SaveUploadedFiles\Exception\Exception;

class File extends Save
{
    private $filename;

    /**
     * @param $filename
     * @return File
     */
    public function file($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function getFile()
    {
        if (!isset($_FILES[$this->filename])) {
            throw new Exception("There is no \"" .$this->filename . "\" in \$_Files", 500);
        }

        if($code = $_FILES[$this->filename]['error']){
            $this->throwErrorException($code);
        }

        return $_FILES[$this->filename];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getFileName()
    {
        return explode('.', $this->getFile()['name'])[0];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getFileType()
    {
        return $this->getFile()['type'];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getFileTmpName()
    {
        return $this->getFile()['tmp_name'];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getFileSize()
    {
        return $this->getFile()['size'];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getBaseNameFile()
    {
        return basename($this->getFile()['name']);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getFileExtension()
    {
        $name = explode('.', $this->getFile()['name']);
        return end($name);
    }

    /**
     * @param $code
     * @throws Exception
     */
    private function throwErrorException($code)
    {
        switch ($code){
            case 1:
                throw new Exception("The uploaded file exceeds the upload_max_filesize directive in php.ini",500);
                break;
            case 2:
                throw new Exception("The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",500);
                break;
            case 3:
                throw new Exception("The uploaded file was only partially uploaded",500);
                break;
            case 4:
                throw new Exception("No file was uploaded",500);
                break;
            case 5:
                throw new Exception("Unknown error occurred.",500);
                break;
            case 6:
                throw new Exception("Missing a temporary folder.",500);
                break;
            case 7:
                throw new Exception("Failed to write file to disk",500);
                break;
            case 8:
                throw new Exception("A PHP extension stopped the file upload",500);
                break;
            default:
                throw new Exception("Unknown error occurred.",500);
        }


    }
}