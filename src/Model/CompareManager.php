<?php

namespace App\Model;

class CompareManager
{
    private function mimeType($image)
    {
        /*returns array with mime type and if its jpg or png. Returns false if it isn't jpg or png*/
        $mime = getimagesize($image);
        $return = array($mime[0],$mime[1]);

        switch ($mime['mime']) {
            case 'image/jpeg':
                $return[] = 'jpg';
                return $return;
            case 'image/png':
                $return[] = 'png';
                return $return;
            default:
                return false;
        }
    }

    private function createImage($image)
    {
        /*retuns image resource or false if its not jpg or png*/
        $mime = $this->mimeType($image);

        if ($mime[2] == 'jpg') {
            return imagecreatefromjpeg($image);
        } elseif ($mime[2] == 'png') {
            return imagecreatefrompng($image);
        } else {
            return false;
        }
    }

    private function resizeImage($image, $source)
    {
        /*resizes the image to a 8x8 squere and returns as image resource*/
        $mime = $this->mimeType($source);

        $trueColor = imagecreatetruecolor(8, 8);

        $source = $this->createImage($image);

        imagecopyresized($trueColor, $source, 0, 0, 0, 0, 8, 8, $mime[0], $mime[1]);

        return $trueColor;
    }

    private function colorMeanValue($image)
    {
        /*returns the mean value of the colors and the list of all pixel's colors*/
        $colorList = [];
        $colorSum = 0;
        for ($color = 0; $color < 8; $color++) {
            for ($pixel = 0; $pixel < 8; $pixel++) {
                $rgb = imagecolorat($image, $color, $pixel);
                $colorList[] = $rgb & 0xFF;
                $colorSum += $rgb & 0xFF;
            }
        }
        return array($colorSum / 64,$colorList);
    }

    private function bits($colorMean)
    {
        /*returns an array with 1 and zeros. If a color is bigger than the mean value of colors it is 1*/
        $bits = [];

        foreach ($colorMean[1] as $color) {
            $bits[] = ($color >= $colorMean[0]) ? 1 : 0;
        }
        return $bits;
    }

    public function compare($picture1, $picture2)
    {
        /*main function. returns the hammering distance of two images' bit value*/
        $image1 = $this->createImage($picture1);
        $image2 = $this->createImage($picture2);

        if (!$image1 || !$image2) {
            return false;
        }

        $image1 = $this->resizeImage($image1, $picture1);
        $image2 = $this->resizeImage($image2, $picture2);

        imagefilter($image1, IMG_FILTER_GRAYSCALE);
        imagefilter($image2, IMG_FILTER_GRAYSCALE);

        $colorMean1 = $this->colorMeanValue($image1);
        $colorMean2 = $this->colorMeanValue($image2);

        $bits1 = $this->bits($colorMean1);
        $bits2 = $this->bits($colorMean2);

        $hammeringDistance = 0;

        for ($a = 0; $a < 64; $a++) {
            if ($bits1[$a] != $bits2[$a]) {
                $hammeringDistance++;
            }
        }
        return $hammeringDistance;
    }
}
