<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 08/02/2018
 * Time: 12:42
 */

namespace AppBundle\Twig;

use AppBundle\Entity\NoteLabel;
use AppBundle\Service\ColorManager;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    /**
     * @var ColorManager
     */
    private $colorManager;


    /**
     * AppExtension constructor.
     * @param ColorManager $colorManager
     */
    public function __construct(ColorManager $colorManager)
    {
        $this->colorManager = $colorManager;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getLabelColor', array($this, 'getLabelColor')),
        );
    }

    public function getLabelColor(NoteLabel $noteLabel)
    {
        $colorRed = ColorManager::COLOR_RED;
        $colorSector = $this->colorManager->hexToRgb($noteLabel->getSector()->getColor());

        $arColorLabel = [
            ColorManager::RED => $colorRed[ColorManager::RED]*(1 - $noteLabel->getRadius()),
            ColorManager::GREEN => $colorSector[ColorManager::GREEN] - ($colorSector[ColorManager::GREEN] - $colorRed[ColorManager::GREEN])*(1 - $noteLabel->getRadius()),
            ColorManager::BLUE => $colorSector[ColorManager::BLUE] - ($colorSector[ColorManager::BLUE] - $colorRed[ColorManager::BLUE])*(1 - $noteLabel->getRadius()),
        ];

        $color = $this->colorManager->hexArrayInRgbString($arColorLabel);
//        $color = $this->colorManager->hexArrayInRgbString($colorSector);

        return $color;
    }

    public function getName()
    {
        return 'app_twig_extension';
    }

}