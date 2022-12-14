<?php

require_once('src/controller/homepage.php');
require_once('src/controller/profile_page.php');
require_once('src/controller/controller.php');
require_once('src/controller/new_post.php');
require_once('src/controller/search_page.php');
require_once('src/controller/close_comment.php');
require_once('src/model/log.php');
require_once('src/model/post.php');
require_once('src/model/account.php');
require_once('src/controller/post_page.php');
require_once('src/model/friend.php');
require_once('src/model/pending_request.php');
require_once('src/model/comment.php');
require_once('src/model/reaction.php');
require_once('src/controller/login.php');
require_once('src/controller/signup.php');
require_once('src/controller/close_post.php');
require_once('src/controller/friend.php');
require_once('src/controller/creating_post.php');
require_once('src/controller/reaction.php');
require_once('src/controller/comment.php');


use Application\Controller\Account\Signup\Signup;
use Application\Controller\Close_Comment\Close_Comment;
use Application\Controller\Close_Post\Close_Post;
use Application\Controller\Comment\Comments;
use Application\Controller\Creating_Post\Creating_Post;
use Application\Controller\Friend\Friend_Request;
use Application\Controller\Login\Login;
use Application\Controller\Reaction\Reactions;
use Application\Controller\Homepage\Homepage;
use Application\Controller\ProfilePage\ProfilePage;
use Application\Controller\Controller\Controller;
use Application\Controller\NewPost\NewPost;
use Application\Controller\SearchPage\Searching;
use Application\Controller\Post_Page\Post_Page;


try {
    session_start();
    $controller = new Controller();
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];
    $connection = isset($_SESSION['username']);

    if (!$connection && $uri !== '/' && $method === 'GET') {    // If the user is not connected and he tries to access a page
        header('Location: /');
        exit();
    }

    if ($connection) { // get controller account
        $controller->Connect($_SESSION['username']);
    }

    if (isset($_POST['id_post_reaction'])) { // reaction to post/comments
        Reactions::execute($controller);
    }

    if (isset($_POST['disconnect'])) { // disconnect
        session_destroy();
        header('Location: /');
    }
    
    if (isset($_GET['new'])) { // new post page
        NewPost::execute($controller);

    } elseif (isset($_POST['comment_content'])) { // new comment
       Comments::execute($controller);

    } elseif (isset($_POST['username']) && 
                isset($_POST['email']) && 
                isset($_POST['password']) && 
                isset($_POST['password_confirm'])) { // sign up
        Signup::execute();

    } elseif (isset($_GET['post'])) {   // post page
        Post_Page::execute($controller);

    } elseif (isset($_POST['username']) && 
                isset($_POST['password'])) { // login
        Login::execute();

    } elseif (isset($_GET['close_post'])) { // delete post
        Close_Post::execute();

    } elseif (isset($_GET['close_comment'])) { // delete comment
        Close_Comment::execute();

    } elseif (!empty($_POST['post_title']) && 
                isset($_POST['post_content'])) { // new post
        Creating_Post::execute($controller);
        Homepage::execute($controller);

    } elseif (isset($_GET['u'])) { // profile page
        Friend_Request::execute($controller);
        $profile_page = ProfilePage::profile_page_new();
        $profile_page->execute($controller);

    } elseif (isset($_GET['search_terms'])) { // searching page 
        if (empty($_GET['search_terms'])) {
            Homepage::execute($controller);
        }
        Searching::execute($controller);

    } else { // homepage
        Homepage::execute($controller);
    }



} catch (Exception $e) {
    $errorMessage = $e->getMessage();

    require('template/error.php');
}