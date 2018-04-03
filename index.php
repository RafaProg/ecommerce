<?php 

session_start();

require_once("vendor/autoload.php");

use Slim\Slim;
use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Model\User;


$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
    $page = new Page();
    
    $page->setTpl('principal');
    
});

$app->get('/admin', function() {
    
    User::loginVerify();
    
    $pageAdmin = new PageAdmin();
    
    $pageAdmin->setTpl('principal');
    
});

$app->get('/admin/login', function(){
    
    $pageAdmin = new PageAdmin(['header'=>false, 'footer'=>false]);
    
    $pageAdmin->setTpl('login');
    
});

$app->post('/admin/login', function(){
    
    User::login($_POST['login'], $_POST['password']);
    
    header("Location: /admin");
    exit;
    
});

$app->get('/admin/logout', function() {
    
    User::logOut();
    
});

$app->get('/admin/users', function() {

    User::loginVerify();

    $users = User::listAll();

    $page = new PageAdmin();

    $page->setTpl('users', array(
        "users"=>$users
    ));

});

$app->get('/admin/users/create', function() {

    User::loginVerify();

    $page = new PageAdmin();

    $page->setTpl('users-create');

});

$app->get('/admin/users/:iduser/delete', function($iduser) {

    User::loginVerify();

    $user = new User();

    $user->get((int)$iduser);

    $user->delete();

    header('LOCATION: /admin/users');
    exit;

});

$app->get('/admin/users/:iduser', function($idUser) {

    User::loginVerify();

    $user = new User();

    $user->get((int)$idUser);

    $page = new PageAdmin();

    $page->setTpl('users-update', array(
        "user"=>$user->getValues()
    ));

});

$app->post('/admin/users/create', function() {

    User::loginVerify();

    $users = new User();

    $_POST['inadmin'] = isset($_POST['inadmin'])?1:0;

    $users->setData($_POST);
    
    $users->save();

    header('LOCATION: /admin/users');
    exit;
    
});

$app->post('/admin/users/:idUser', function($idUser) { 

    User::loginVerify();

    $user = new User();

    $_POST['inadmin'] = isset($_POST['inadmin'])?1:0;

    $user->get((int)$idUser);
    
    $user->setData($_POST);

    $user->update();

    header('LOCATION: /admin/users');
    exit;

});

$app->run();



 