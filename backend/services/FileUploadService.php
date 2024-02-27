<?php
namespace backend\services;

use Yii;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;
use yii\imagine\Image;

/** Use X axis to scale image. */
define('IMAGES_SCALE_AXIS_X', 1);
/** Use Y axis to scale image. */
define('IMAGES_SCALE_AXIS_Y', 2);
/** Use both X and Y axes to calc image scale. */
define('IMAGES_SCALE_AXIS_BOTH', IMAGES_SCALE_AXIS_X ^ IMAGES_SCALE_AXIS_Y);
/** Compression rate for JPEG image format. */
define('JPEG_COMPRESSION_QUALITY', 90);
/** Compression rate for PNG image format. */
define('PNG_COMPRESSION_QUALITY', 9);

class FileUploadService
{
    public static function uploadFile(UploadedFile $file, $uploadPath,$fileName)
    {
          // Check if the upload directory exists, create it if not
          if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        $fileName=sprintf('%s.%s',$fileName,$file->extension);
        
        // Path to save the original uploaded file
        $originalFilePath = $uploadPath . '/' . $fileName;
        
        // Path to save the resized file for restuarante_mobile
        $resizedMobileFolder = $uploadPath . '/mobil/';
        if (!is_dir($resizedMobileFolder)) {
            mkdir($resizedMobileFolder, 0777, true);
        }
        $resizedMobilePath=$resizedMobileFolder.$fileName;

        // Save the original uploaded file
        if ($file->saveAs($originalFilePath)) {
        //     $image=Image::getImagine()
        //     ->open($originalFilePath);
        //     // Calculate the new dimensions while maintaining aspect ratio
        //     $originalWidth = $image->getSize()->getWidth();
        //     $originalHeight = $image->getSize()->getHeight();

        //     $newWidth = 400;
        //     $newHeight = 200;//floor($originalHeight * ($newWidth / $originalWidth));
        //     // Resize the image
        //    $image
        //         ->resize(new \Imagine\Image\Box($newWidth, $newHeight))
        //         ->save($resizedMobilePath);
            self::scaleImage($originalFilePath,$resizedMobilePath, 400,300, null, IMAGES_SCALE_AXIS_BOTH);
            // Return the path to the original uploaded file
            return $originalFilePath;
        }
        return null;
    }

    private static function createThumbnail($imageName,$newWidth,$newHeight,$uploadDir,$moveToDir)
{
    $path = $uploadDir . '/' . $imageName;

    $mime = getimagesize($path);

    if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
    if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
    if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
    if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }

    $old_x = imageSX($src_img);
    $old_y = imageSY($src_img);

    if($old_x > $old_y)
    {
        $thumb_w    =   $newWidth;
        $thumb_h    =   $old_y/$old_x*$newWidth;
    }

    if($old_x < $old_y)
    {
        $thumb_w    =   $old_x/$old_y*$newHeight;
        $thumb_h    =   $newHeight;
    }

    if($old_x == $old_y)
    {
        $thumb_w    =   $newWidth;
        $thumb_h    =   $newHeight;
    }

    $dst_img        =   ImageCreateTrueColor($thumb_w,$thumb_h);

    imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);


    // New save location
    $new_thumb_loc = $moveToDir . $imageName;

    if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }
    if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
    if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
    if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

    imagedestroy($dst_img);
    imagedestroy($src_img);
    return $result;
}



/**
 * Scales an image with save aspect ration for X, Y or both axes.
 *
 * @param string $sourceFile Absolute path to source image.
 * @param string $destinationFile Absolute path to scaled image.
 * @param int|null $toWidth Maximum `width` of scaled image.
 * @param int|null $toHeight Maximum `height` of scaled image.
 * @param int|null $percent Percent of scale of the source image's size.
 * @param int $scaleAxis Determines how of axis will be used to scale image.
 *
 * May take a value of {@link IMAGES_SCALE_AXIS_X}, {@link IMAGES_SCALE_AXIS_Y} or {@link IMAGES_SCALE_AXIS_BOTH}.
 * @return bool True on success or False on failure.
 */
private static function scaleImage($sourceFile, $destinationFile, $toWidth = null, $toHeight = null, $percent = null, $scaleAxis = IMAGES_SCALE_AXIS_BOTH) {
    $toWidth = (int)$toWidth;
    $toHeight = (int)$toHeight;
    $percent = (int)$percent;
    $result = false;

    if (($toWidth | $toHeight | $percent)
        && file_exists($sourceFile)
        && (file_exists(dirname($destinationFile)) || mkdir(dirname($destinationFile), 0777, true))) {

        $mime = getimagesize($sourceFile);

        if (in_array($mime['mime'], ['image/jpg', 'image/jpeg', 'image/pjpeg'])) {
            $src_img = imagecreatefromjpeg($sourceFile);
        } elseif ($mime['mime'] == 'image/png') {
            $src_img = imagecreatefrompng($sourceFile);
        }

        $original_width = imagesx($src_img);
        $original_height = imagesy($src_img);

        if ($scaleAxis == IMAGES_SCALE_AXIS_BOTH) {
            if (!($toWidth | $percent)) {
                $scaleAxis = IMAGES_SCALE_AXIS_Y;
            } elseif (!($toHeight | $percent)) {
                $scaleAxis = IMAGES_SCALE_AXIS_X;
            }
        }

        if ($scaleAxis == IMAGES_SCALE_AXIS_X && $toWidth) {
            $scale_ratio = $original_width / $toWidth;
        } elseif ($scaleAxis == IMAGES_SCALE_AXIS_Y && $toHeight) {
            $scale_ratio = $original_height / $toHeight;
        } elseif ($percent) {
            $scale_ratio = 100 / $percent;
        } else {
            $scale_ratio_width = $original_width / $toWidth;
            $scale_ratio_height = $original_height / $toHeight;

            if ($original_width / $scale_ratio_width < $toWidth && $original_height / $scale_ratio_height < $toHeight) {
                $scale_ratio = min($scale_ratio_width, $scale_ratio_height);
            } else {
                $scale_ratio = max($scale_ratio_width, $scale_ratio_height);
            }
        }

        $scale_width = $original_width / $scale_ratio;
        $scale_height = $original_height / $scale_ratio;

        $dst_img = imagecreatetruecolor($scale_width, $scale_height);

        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $scale_width, $scale_height, $original_width, $original_height);

        if (in_array($mime['mime'], ['image/jpg', 'image/jpeg', 'image/pjpeg'])) {
            $result = imagejpeg($dst_img, $destinationFile, JPEG_COMPRESSION_QUALITY);
        } elseif ($mime['mime'] == 'image/png') {
            $result = imagepng($dst_img, $destinationFile, PNG_COMPRESSION_QUALITY);
        }

        imagedestroy($dst_img);
        imagedestroy($src_img);
    }

    return $result;
}
}
