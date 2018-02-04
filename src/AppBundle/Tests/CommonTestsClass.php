<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 18/11/2017
 * Time: 14:17
 */
namespace AppBundle\Tests;

class CommonTestsClass
{
    public static function getAllFixturesArray()
    {
        return [
            'AppBundle\DataFixtures\ORM\LoadUsersData',
            'AppBundle\DataFixtures\ORM\LoadCategoriesData',
            'AppBundle\DataFixtures\ORM\LoadCirclesData',
            'AppBundle\DataFixtures\ORM\LoadNoteLabelsData',
            'AppBundle\DataFixtures\ORM\LoadNotesData',
            'AppBundle\DataFixtures\ORM\LoadSectorsData',
        ];
    }
}