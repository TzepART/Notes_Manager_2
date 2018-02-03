#!/usr/bin/env bash
#php bin/console generate:doctrine:entity --entity=AppBundle:Note --fields="title:string(length=256) text:text"
php bin/console generate:doctrine:entity --entity=AppBundle:Category --fields="name:string"
php bin/console generate:doctrine:entity --entity=AppBundle:Circle --fields="name:string user:int countLayer:int"
php bin/console generate:doctrine:entity --entity=AppBundle:Sector --fields="category:int circle:int name:string"
php bin/console generate:doctrine:entity --entity=AppBundle:NoteLabel --fields="angle:float radius:float circle:int note:int"
