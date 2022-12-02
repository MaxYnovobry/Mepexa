<?php
namespace Application\Controller\Homepage;

require_once('src/controller/controller.php');
require_once('src/model/post.php');

use Application\Model\Post\Feed; 
use Application\Controller\Controller\Controller;
use Application\Model\Account\Account;

class Homepage
{
    public static function execute(Controller $controller)
    {
        $title = "Мережа";

        $feed = new Feed();
        $feed->GenerateFeed($controller->GetAccount()->GetId());

        require('template/homepage.php');
    }
}