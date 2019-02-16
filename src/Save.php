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

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var string
     */
    private $file_path = '';

    /**
     * @var bool
     */
    private $override;
    /**
     * @var mixed
     */
    private $save_as;

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
     */
    public function save($path)
    {
        $this->path = $path;

        try {
            $this->validate();
            $this->moveFile();
            return $this->message(true, "The file \"" . $this->getBaseNameFile() . "\" has been uploaded.");
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
     * @throws Exception
     */
    private function moveFile()
    {
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }

        if (null !== $this->getSaveAs()) {
            $this->file_path = $this->path . "/" . $this->getSaveAs() . "." . $this->getFileExtension();
        } else {
            $this->file_path = $this->path . "/" . $this->getbasenamefile();
        }

        if (!$this->isOverride() && file_exists($this->file_path)) {
            throw new Exception("Sorry, file already exists. The file path: \"" . $this->file_path . "\"");
        }

        if (!move_uploaded_file($this->getFileTmpName(), $this->file_path)) {
            throw new Exception("Sorry, there was an error uploading your file. Maybe the path does not have permission to write");
        }
    }

    /**
     * @param $status
     * @param $message
     * @return array
     */
    private function message($status, $message)
    {
        $message = [
            'status' => $status,
            'message' => $message,
        ];

        try {
            $message = array_merge(
                $message,
                [
                    'file_details' => [
                        'name' => $this->getFileName(),
                        'type' => $this->getFileType(),
                        'tmp_name' => $this->getFileTmpName(),
                        'size' => $this->getFileSize(),
                        'extension' => $this->getFileExtension(),
                        'basename' => $this->getbasenamefile(),
                        'save_as' => (null === $this->getSaveAs()) ? $this->getbasenamefile() : $this->getSaveAs() . '.' . $this->getFileExtension(),
                        'dir_path' => $this->path,
                        'file_path' => $this->file_path,
                        'upload_datetime' => date("Y-m-d H:i:s"),
                        'stat' => stat($this->file_path)
                    ]
                ]
            );
        } catch (Exception $e) {}

        return $message;
    }

    /**
     * @param bool $override
     * @return Save
     */
    public function setOverride(bool $override): Save
    {
        $this->override = $override;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOverride(): bool
    {
        return $this->override;
    }

    /**
     * @param mixed $save_as
     * @return Save
     */
    public function setSaveAs($save_as): Save
    {
        $this->save_as = $save_as;
        return $this;
    }

    /**
     * @return string
     */
    public function getSaveAs()
    {
        return $this->save_as;
    }
}