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
use AYazdanpanah\SaveUploadedFiles\Exception\SaveExceptionInterface;

abstract class Save implements FileInterface
{

    private $validator;

    /**
     * Save constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $path
     * @return array
     * @throws Exception
     */
    public function save($path)
    {
        try {
            $this->validate();
            $this->moveFile($path);
            return $this->message(true, "The file uploaded with success");
        } catch (SaveExceptionInterface $e) {
            return $this->message(false, $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function validate()
    {
        $this->validator
            ->setFileSize($this->getFileSize())
            ->setFileExtension($this->getFileExtension())
            ->validate();
    }

    /**
     * @param $path
     * @throws Exception
     */
    private function moveFile($path)
    {
        if (!move_uploaded_file($this->getFileTmpName(), $path . "/". $this->getbasenamefile())) {
            throw new Exception("The file was not saved!");
        }
    }

    /**
     * @param $status
     * @param $message
     * @return array
     * @throws Exception
     */
    private function message($status, $message)
    {
        return [
            'status' => $status,
            'message' => $message,
            'file_details' => [
                'name' => $this->getFileName(),
                'type' => $this->getFileType(),
                'tmp_name' => $this->getFileTmpName(),
                'size' => $this->getFileSize(),
                'extension' => $this->getFileExtension(),
                'basename' => $this->getbasenamefile()
            ]
        ];
    }
}