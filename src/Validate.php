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

abstract class Validate implements ValidatorInterface
{
    private $file_size;
    private $file_extension;

    /**
     * @throws Exception
     */
    public function validate()
    {
        if($this->getMaxSize() < $this->getFileSize()){
            throw new Exception("The uploaded file was too large");
        }

        if($this->getMinSize() > $this->getFileSize()){
            throw new Exception("The uploaded file was too small");
        }

        if(!in_array($this->getFileExtension(),$this->getType()) && !current($this->getType()) == '*'){
            throw new Exception("The uploaded file was too small");
        }
    }

    /**
     * @param mixed $file_size
     * @return Validate
     */
    public function setFileSize($file_size)
    {
        $this->file_size = $file_size;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileSize()
    {
        return $this->file_size;
    }

    /**
     * @param mixed $file_extension
     * @return Validate
     */
    public function setFileExtension($file_extension)
    {
        $this->file_extension = $file_extension;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileExtension()
    {
        return $this->file_extension;
    }
}