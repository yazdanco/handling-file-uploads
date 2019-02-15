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
     * @var bool
     */
    private $override;
    /**
     * @var bool
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
     * @throws Exception
     */
    public function save($path)
    {
        try {
            $this->validate();
            $this->moveFile($path);
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
     * @param $path
     * @throws Exception
     */
    private function moveFile($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        if (null !== $this->getSaveAs()) {
            $file_path = $path . "/" . $this->getSaveAs() . "." . $this->getFileExtension();
        }else{
            $file_path = $path . "/" . $this->getbasenamefile();
        }

        if (!$this->isOverride() && file_exists($file_path)) {
            throw new Exception("Sorry, file already exists. The file path: " . $file_path);
        }

        if (!move_uploaded_file($this->getFileTmpName(), $file_path)) {
            throw new Exception("Sorry, there was an error uploading your file");
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
        $message = [
            'status' => $status,
            'message' => $message,

        ];

        if ($status) {
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
                        'save_as' => (null === $this->getSaveAs()) ? $this->getbasenamefile() : $this->getSaveAs()
                    ]
                ]
            );
        }

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