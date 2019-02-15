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

use AYazdanpanah\SaveUploadedFiles\Exception\SaveExceptionInterface;
use AYazdanpanah\SaveUploadedFiles\Files;

if (! function_exists('save_as')) {
    /**
     * @param $files_config
     * @return mixed
     */
    function save_as($files_config): array
    {
        try {
            return Files::files($files_config);
        } catch (SaveExceptionInterface $e) {
            return [
                'status' => false,
                'message' => "error: " . $e->getMessage()
            ];
        }
    }
}