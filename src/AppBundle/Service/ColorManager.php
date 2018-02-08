<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.03.17
 * Time: 12:54
 */
namespace AppBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ColorManager
 * @package AppBundle\Service
 */
class ColorManager
{
    const RED = 'red';
    const GREEN = 'green';
    const BLUE = 'blue';

    const COLOR_RED = [
        self::RED => 255,
        self::GREEN => 0,
        self::BLUE => 0,
    ];


    /**
     * @var ObjectManager $em
     */
    protected $em;

    /**
     * @var ContainerInterface $container
     * */
    protected $container;


    /**
     * @param ContainerInterface $container
     * @param ObjectManager $em
     */
    public function __construct(ContainerInterface $container, ObjectManager $em)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * Method for converting RgbString in array by colors
     * @param string $hex
     * @return array
     */
    public function hexToRgb($hex) {
        $hex = str_replace('#', '', $hex);
        if ( strlen($hex) == 6 ) {
            $rgb[self::RED] = (int) hexdec(substr($hex, 0, 2));
            $rgb[self::GREEN] = (int) hexdec(substr($hex, 2, 2));
            $rgb[self::BLUE] = (int) hexdec(substr($hex, 4, 2));
        }
        else if ( strlen($hex) == 3 ) {
            $rgb[self::RED] = (int) hexdec(str_repeat(substr($hex, 0, 1), 2));
            $rgb[self::GREEN] = (int) hexdec(str_repeat(substr($hex, 1, 1), 2));
            $rgb[self::BLUE] = (int) hexdec(str_repeat(substr($hex, 2, 1), 2));
        }
        else {
            $rgb[self::RED] = '0';
            $rgb[self::GREEN] = '0';
            $rgb[self::BLUE] = '0';
        }
        return $rgb;
    }

    /**
     * Method for converting array by colors in RgbString
     * @param array $tempColor
     * @return string
     */
    public function hexArrayInRgbString($tempColor) {
        $rgb = 'rgb('.(int) $tempColor[self::RED].', '.(int) $tempColor[self::GREEN].', '.(int) $tempColor[self::BLUE].')';
        return $rgb;
    }
}
