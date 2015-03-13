<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/contact.php";

    use Symfony\Component\Debug\Debug;
    Debug::enable();

    session_start();

    if (empty($_SESSION['list_of_contacts'])) {
      $_SESSION['list_of_contacts'] = array();
    }

    $app = new Silex\Application();

    $app['debug'] = true;


    $contacts = Contact::getAll();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
      'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
      return $app['twig']->render('home.twig', array('contacts' => Contact::getAll()));
    });

    $app->get("/create_new", function() use ($app) {
      return $app['twig']->render('create_new.twig');
    });

    $app->post("/create_new", function() use ($app) {
        $new_contact = new Contact($_POST['name'], $_POST['phone'], $_POST['address']);
        $new_contact->save();
      return $app['twig']->render('confirm.twig', array ('newcontact'=>$new_contact));
    });


    $app->get("/confirm", function() use ($app) {
      return $app['twig']->render('/confirm.twig');
    });

    $app->get('delete', function() use ($app) {
      Contact::deleteAll();
      return $app['twig']->render('delete.twig');
    });


    return $app;
?>
