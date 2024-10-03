<?php

namespace App\Http\Middleware\Fileupload;
use Closure;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class FileSecurityCheck {

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        Log::info("file upoload middleware is running");

        //image extensions tocheck
        $excluded_extensions = $this->excludedExtensions();
        $image_extensions = $this->imageExtensions();
        $image_mime_types = $this->imageMimeTypes();
        $disallowed_patterns = $this->disallowedPatterns();

        //was a file uplaoded
        if ($file = $request->file('file')) {

            //get the file extension
            $extension = $file->getClientOriginalExtension();

            //get filename
            $filename = $file->getClientOriginalName();

            //validate extension
            if (in_array($extension, $excluded_extensions)) {
                Log::info("the file has an extension that is not allowed", ['process' => '[file-upload]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'file name' => $filename, 'extension' => $extension]);
                abort(409, __('lang.file_type_not_allowed'));
            }

            //[IMAGES] - This file has an image extension
            if (in_array($extension, $image_extensions)) {
                //try and read the image type
                try {
                    //is it an image
                    $imagedata = @getimagesize($file);
                    if (is_array($imagedata)) {
                        //no valid mime type
                        if (!isset($imagedata['mime'])) {
                            Log::info("the file does not have a valid mime type", ['process' => '[file-upload]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'file name' => $filename, 'extension' => $extension]);
                            abort(409, __('lang.file_type_not_allowed'));
                        }
                        //mime type is not allowed
                        if (!in_array($imagedata['mime'], $image_mime_types)) {
                            Log::info("the file does not have a valid mime type", ['process' => '[file-upload]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'file name' => $filename, 'extension' => $extension]);
                            abort(409, __('lang.file_type_not_allowed'));
                        }
                    } else {
                        //this is not really an image
                        Log::info("the file has an image extension but could not be read as an image", ['process' => '[file-upload]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'file name' => $filename, 'extension' => $extension]);
                        abort(409, __('lang.file_type_not_allowed'));
                    }
                } catch (Exception $e) {
                    //this is not really an image
                    Log::info("the file has an image extension but could not be read as an image", ['process' => '[file-upload]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'file name' => $filename, 'extension' => $extension]);
                    abort(409, __('lang.file_type_not_allowed'));
                }
            }
        } else {
            //the user posted this file using a means other than the dropzone feature
            Log::info("a file was uploaded but not using the expected field name 'file' as sent used by dropzone", ['process' => '[file-upload]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'file name' => $filename, 'extension' => $extension]);
            abort(409, __('lang.file_type_not_allowed'));
        }

        return $next($request);
    }

    /**
     * array of extensions that are not safe
     * @return array
     */
    private function excludedExtensions() {

        return [
            'php',
            'phar',
            'exe',
            'html',
            'htm',
            'htaccess',
            'pl',
            'cgi',
            'js',
            'script',
            'sh',
            'asp',
            'ph3',
            'php4',
            'php3',
            'php5',
            'php6',
            'php7',
            'php8',
            'phtm',
            'phtml',
            'sql',
            'bak',
            'config',
            'fla',
            'inc',
            'log',
            'ini',
            'dist',
        ];

    }

    /**
     * array of image file extensions
     * @return array
     */
    private function imageExtensions() {

        return [
            'jpg',
            'jpeg',
            'jfif',
            'pjpeg',
            'pjp',
            'apng',
            'png',
            'avif',
            'gif',
            'svg',
            'webp',
            'bmp',
            'ico',
            'cur',
            'tif',
            'tiff',
            'xbm',
        ];

    }

    /**
     * array of image mime type to match the image extensions
     * @return array
     */
    private function imageMimeTypes() {

        return [
            'image/apng',
            'image/avif',
            'image/bmp',
            'image/gif',
            'image/vnd.microsoft.icon',
            'image/x-icon',
            'image/jpeg',
            'image/png',
            'image/svg+xml',
            'image/tiff',
            'image/webp',
            'image/xbm',
            'image-xbitmap',
        ];

    }

    /**
     * array of strings we do not expect to find at the start of the file
     * @return array
     */
    private function disallowedPatterns() {

        return [
            '<?php',
            'phar',
        ];

    }
}