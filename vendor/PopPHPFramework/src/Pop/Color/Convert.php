<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/nicksagona/PopPHP
 * @category   Pop
 * @package    Pop_Color
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Color;

/**
 * Convert color class
 *
 * @category   Pop
 * @package    Pop_Color
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.2.0
 */
class Convert
{

    /**
     * Method to convert a color space object to a CMYK object
     *
     * @param  ColorInterface $color
     * @throws Exception
     * @return \Pop\Color\Cmyk
     */
    public static function toCmyk(ColorInterface $color)
    {
        $class = get_class($color);

        if ($class == 'Pop\Color\Cmyk') {
            throw new Exception('That color space object is already that type.');
        }

        $type = strtolower(substr($class, (strrpos($class, '\\') + 1)));
        $method = $type . 'ToCmyk';

        return self::$method($color);
    }

    /**
     * Method to convert a color space object to a hex RGB object
     *
     * @param  ColorInterface $color
     * @throws Exception
     * @return \Pop\Color\Hex
     */
    public static function toHex(ColorInterface $color)
    {
        $class = get_class($color);

        if ($class == 'Pop\Color\Hex') {
            throw new Exception('That color space object is already that type.');
        }

        $type = strtolower(substr($class, (strrpos($class, '\\') + 1)));
        $method = $type . 'ToHex';

        return self::$method($color);
    }

    /**
     * Method to convert a color space object to an HSB object
     *
     * @param  ColorInterface $color
     * @throws Exception
     * @return \Pop\Color\Hsb
     */
    public static function toHsb(ColorInterface $color)
    {
        $class = get_class($color);

        if ($class == 'Pop\Color\Hsb') {
            throw new Exception('That color space object is already that type.');
        }

        $type = strtolower(substr($class, (strrpos($class, '\\') + 1)));
        $method = $type . 'ToHsb';

        return self::$method($color);
    }

    /**
     * Method to convert a color space object to a LAB object
     *
     * @param  ColorInterface $color
     * @throws Exception
     * @return \Pop\Color\Lab
     */
    public static function toLab(ColorInterface $color)
    {
        $class = get_class($color);

        if ($class == 'Pop\Color\Lab') {
            throw new Exception('That color space object is already that type.');
        }

        $type = strtolower(substr($class, (strrpos($class, '\\') + 1)));
        $method = $type . 'ToLab';

        return self::$method($color);
    }

    /**
     * Method to convert a color space object to an integer RGB object
     *
     * @param  ColorInterface $color
     * @throws Exception
     * @return \Pop\Color\Rgb
     */
    public static function toRgb(ColorInterface $color)
    {
        $class = get_class($color);

        if ($class == 'Pop\Color\Rgb') {
            throw new Exception('That color space object is already that type.');
        }

        $type = strtolower(substr($class, (strrpos($class, '\\') + 1)));
        $method = $type . 'ToRgb';

        return self::$method($color);
    }

    /**
     * Method to convert an integer RGB object to a hex RGB object
     *
     * @param  Rgb $rgb
     * @return \Pop\Color\Hex
     */
    public static function rgbToHex(Rgb $rgb)
    {
        $hex = dechex($rgb->getRed()) . dechex($rgb->getGreen()) . dechex($rgb->getBlue());
        return new Hex($hex);
    }

    /**
     * Method to convert an integer RGB object to a CMYK object
     *
     * @param  Rgb $rgb
     * @return \Pop\Color\Cmyk
     */
    public static function rgbToCmyk(Rgb $rgb)
    {
        $K = 1;

        // Calculate CMY.
        $cyan = 1 - ($rgb->getRed() / 255);
        $magenta = 1 - ($rgb->getGreen() / 255);
        $yellow = 1 - ($rgb->getBlue() / 255);

        // Calculate K.
        if ($cyan < $K) {
            $K = $cyan;
        }
        if ($magenta < $K) {
            $K = $magenta;
        }
        if ($yellow < $K) {
            $K = $yellow;
        }

        if ($K == 1) {
            $cyan = 0;
            $magenta = 0;
            $yellow = 0;
        } else {
            $cyan = round((($cyan - $K) / (1 - $K)) * 100);
            $magenta = round((($magenta - $K) / (1 - $K)) * 100);
            $yellow = round((($yellow - $K) / (1 - $K)) * 100);
        }

        $black = round($K * 100);

        return new Cmyk($cyan, $magenta, $yellow, $black);
    }

    /**
     * Method to convert an integer RGB object to an HSB object
     *
     * @param  Rgb $rgb
     * @return \Pop\Color\Hsb
     */
    public static function rgbToHsb(Rgb $rgb)
    {
        // Calculate the hue.
        $r = $rgb->getRed();
        $g = $rgb->getGreen();
        $b = $rgb->getBlue();

        $min = min($r, min($g, $b));
        $max = max($r, max($g, $b));
        $delta = $max - $min;
        $h = 0;

        if ($delta > 0) {
            if ($max == $r && $max != $g) $h += ($g - $b) / $delta;
            if ($max == $g && $max != $b) $h += (2 + ($b - $r) / $delta);
            if ($max == $b && $max != $r) $h += (4 + ($r - $g) / $delta);
            $h /= 6;
        }

        // Calculate the saturation and brightness.
        $r = $rgb->getRed() / 255;
        $g = $rgb->getGreen() / 255;
        $b = $rgb->getBlue() / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $b = $max;
        $d = $max - $min;
        $s = ($d == 0) ? 0 : $d / $max;

        return new Hsb(round($h * 360), round($s * 100), round($b * 100));
    }

    /**
     * Method to convert an integer RGB object to a LAB object
     *
     * @param  Rgb $rgb
     * @return \Pop\Color\Lab
     */
    public static function rgbToLab(Rgb $rgb)
    {
        $r = $rgb->getRed() / 255;
        $g = $rgb->getGreen() / 255;
        $b = $rgb->getBlue() / 255;

        if ($r > 0.04045) {
            $r = pow((($r + 0.055 ) / 1.055), 2.4);
        } else {
            $r = $r / 12.92;
        }
        if ($g > 0.04045) {
            $g = pow((($g + 0.055 ) / 1.055), 2.4);
        } else {
            $g = $g / 12.92;
        }
        if ($b > 0.04045) {
            $b = pow((($b + 0.055 ) / 1.055), 2.4);
        } else {
            $b = $b / 12.92;
        }

        $r = $r * 100;
        $g = $g * 100;
        $b = $b * 100;

        $x = (($r * 0.4124) + ($g * 0.3576) + ($b * 0.1805)) / 95.047;
        $y = (($r * 0.2126) + ($g * 0.7152) + ($b * 0.0722)) / 100.000;
        $z = (($r * 0.0193) + ($g * 0.1192) + ($b * 0.9505)) / 108.883;

        if ($x > 0.008856) {
            $x = pow($x, (1/3));
        } else {
            $x = (7.787 * $x) + (16 / 116);
        }
        if ($y > 0.008856) {
            $y = pow($y, (1/3));
        } else {
            $y = (7.787 * $y) + (16 / 116);
        }
        if ($z > 0.008856) {
            $z = pow($z, (1/3));
        } else {
            $z = (7.787 * $z) + (16 / 116);
        }

        $l = (116 * $y) - 16;
        $a = 500 * ($x - $y);
        $b = 200 * ($y - $z);

        return new Lab($l, $a, $b);
    }

    /**
     * Method to convert a CMYK object to an integer RGB object
     *
     * @param  Cmyk $cmyk
     * @return \Pop\Color\Rgb
     */
    public static function cmykToRgb(Cmyk $cmyk)
    {
        $cmykAry = array();

        // Calculate CMY.
        $cmykAry['c'] = $cmyk->getCyan() / 100;
        $cmykAry['m'] = $cmyk->getMagenta() / 100;
        $cmykAry['y'] = $cmyk->getYellow() / 100;
        $cmykAry['k'] = $cmyk->getBlack() / 100;

        $cyan = (($cmykAry['c'] * (1 - $cmykAry['k'])) + $cmykAry['k']);
        $magenta = (($cmykAry['m'] * (1 - $cmykAry['k'])) + $cmykAry['k']);
        $yellow = (($cmykAry['y'] * (1 - $cmykAry['k'])) + $cmykAry['k']);

        // Calculate RGB.
        $r = round((1 - $cyan) * 255);
        $g = round((1 - $magenta) * 255);
        $b = round((1 - $yellow) * 255);

        return new Rgb($r, $g, $b);
    }

    /**
     * Method to convert a CMYK object to a hex RGB object
     *
     * @param  Cmyk $cmyk
     * @return \Pop\Color\Hex
     */
    public static function cmykToHex(Cmyk $cmyk)
    {
        return self::rgbToHex(self::cmykToRgb($cmyk));
    }

    /**
     * Method to convert a CMYK object to an HSB object
     *
     * @param  Cmyk $cmyk
     * @return \Pop\Color\Hsb
     */
    public static function cmykToHsb(Cmyk $cmyk)
    {
        return self::rgbToHsb(self::cmykToRgb($cmyk));
    }

    /**
     * Method to convert a CMYK object to a LAB object
     *
     * @param  Cmyk $cmyk
     * @return \Pop\Color\Lab
     */
    public static function cmykToLab(Cmyk $cmyk)
    {
        return self::rgbToLab(self::cmykToRgb($cmyk));
    }

    /**
     * Method to convert an HSB object to an integer RGB object
     *
     * @param  Hsb $hsb
     * @return \Pop\Color\Rgb
     */
    public static function hsbToRgb(Hsb $hsb)
    {
        $s = $hsb->getSaturation() / 100;
        $v = $hsb->getBrightness() / 100;

        if ($hsb->getSaturation() == 0) {
            $r = round($v * 255);
            $g = round($v * 255);
            $b = round($v * 255);
        } else {
            $h = $hsb->getHue() / 360;
            $h = $h * 6;
            if ($h == 6) {
                $h = 0;
            }

            $i = floor($h);
            $var1 = $v * (1 - $s);
            $var2 = $v * (1 - ($s * ($h - $i)));
            $var3 = $v * (1 - ($s * (1 - ($h - $i))));

            switch ($i) {
                case 0:
                    $r = $v;
                    $g = $var3;
                    $b = $var1;
                    break;
                case 1:
                    $r = $var2;
                    $g = $v;
                    $b = $var1;
                    break;
                case 2:
                    $r = $var1;
                    $g = $v;
                    $b = $var3;
                    break;
                case 3:
                    $r = $var1;
                    $g = $v;
                    $b = $var3;
                    break;
                case 4:
                    $r = $var3;
                    $g = $var1;
                    $b = $v;
                    break;
                default:
                    $r = $v;
                    $g = $var1;
                    $b = $var2;
            }

            $r = round($r * 255);
            $g = round($g * 255);
            $b = round($b * 255);
        }

        return new Rgb($r, $g, $b);
    }

    /**
     * Method to convert an HSB object to a hex RGB object
     *
     * @param  Hsb $hsb
     * @return \Pop\Color\Hex
     */
    public static function hsbToHex(Hsb $hsb)
    {
        return self::rgbToHex(self::hsbToRgb($hsb));
    }

    /**
     * Method to convert an HSB object to a CMYK object
     *
     * @param  Hsb $hsb
     * @return \Pop\Color\Cmyk
     */
    public static function hsbToCmyk(Hsb $hsb)
    {
        return self::rgbToCmyk(self::hsbToRgb($hsb));
    }

    /**
     * Method to convert an HSB object to a LAB object
     *
     * @param  Hsb $hsb
     * @return \Pop\Color\Lab
     */
    public static function hsbToLab(Hsb $hsb)
    {
        return self::rgbToLab(self::hsbToRgb($hsb));
    }

    /**
     * Method to convert a LAB object to an integer RGB object
     *
     * @param  Lab $lab
     * @return \Pop\Color\Rgb
     */
    public static function labToRgb(Lab $lab)
    {
        $y = ($lab->getL() + 16) / 116;
        $x = ($lab->getA() / 500) + $y;
        $z = $y - ($lab->getB() / 200);

        if (pow($y, 3) > 0.008856) {
            $y = pow($y, 3);
        } else {
            $y = ($y - (16 / 116)) / 7.787;
        }
        if (pow($x, 3) > 0.008856) {
            $x = pow($x, 3);
        } else {
            $x = ($x - (16 / 116)) / 7.787;
        }
        if (pow($z, 3) > 0.008856) {
            $z = pow($z, 3);
        } else {
            $z = ($z - (16 / 116)) / 7.787;
        }

        $x = ($x * 95.047) / 100;
        $y = ($y * 100.000) / 100;
        $z = ($z * 108.883) / 100;

        $r = ($x * 3.2406) + ($y * -1.5372) + ($z * -0.4986);
        $g = ($x * -0.9689) + ($y * 1.8758) + ($z *  0.0415);
        $b = ($x * 0.0557) + ($y * -0.2040) + ($z *  1.0570);

        if ($r > 0.0031308) {
            $r = 1.055 * (pow($r, (1 / 2.4)) - 0.055);
        } else {
            $r = 12.92 * $r;
        }
        if ($g > 0.0031308) {
            $g = 1.055 * (pow($g, (1 / 2.4)) - 0.055);
        } else {
            $g = 12.92 * $g;
        }
        if ($b > 0.0031308) {
            $b = 1.055 * (pow($b, (1 / 2.4)) - 0.055);
        } else {
            $b = 12.92 * $b;
        }

        if ($r > 1) {
            $r -= 1;
        }
        if ($g > 1) {
            $g -= 1;
        }
        if ($b > 1) {
            $b -= 1;
        }

        $r = round($r * 255);
        $g = round($g * 255);
        $b = round($b * 255);

        return new Rgb($r, $g, $b);
    }

    /**
     * Method to convert a LAB object to a hex RGB object
     *
     * @param  Lab $lab
     * @return \Pop\Color\Hex
     */
    public static function labToHex(Lab $lab)
    {
        return self::rgbToHex(self::labToRgb($lab));
    }

    /**
     * Method to convert a LAB object to a CMYK object
     *
     * @param  Lab $lab
     * @return \Pop\Color\Cmyk
     */
    public static function labToCmyk(Lab $lab)
    {
        return self::rgbToCmyk(self::labToRgb($lab));
    }

    /**
     * Method to convert a LAB object to an HSB object
     *
     * @param  Lab $lab
     * @return \Pop\Color\Hsb
     */
    public static function labToHsb(Lab $lab)
    {
        return self::rgbToHsb(self::labToRgb($lab));
    }

}
