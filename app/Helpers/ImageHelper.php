<?php

namespace App\Helpers;

class ImageHelper
{
    public static function upload($image): string
    {
        $imageName = time().'.'.$image->extension();
        $image->storeAs('public/images', $imageName);

        return self::getURL($imageName);
    }

    public static function getURL($imageName)
    {
        return asset('storage/images/' . $imageName);
    }
}
