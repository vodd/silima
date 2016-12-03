<?php
$container = $app->getContainer();
$container['debug'] = function (){
    return true;
};
$container['csrf'] = function ($container) {
    return new \Slim\Csrf\Guard;
};
$container['logger'] = function($container) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['view'] = function ($container) {
    $dir = dirname(__DIR__);
    $view = new \Slim\Views\Twig($dir.'/app/views', [
        'cache' => false //$dir.'/tmp/cache'
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['db'] =function ($container){
    //$db = $container['setting']['db'];
    $pdo = new PDO('mysql:host=localhost;dbname=slim','vod','hploupla');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$container['mailer'] = function ($container){
    if($container->debug){
        //$transport = Swift_SmtpTransport::newInstance('localhost',1025);
        $transport = Swift_SmtpTransport::newInstance()
            ->setHost('mail.respawn-agency.com')
            ->setPort(587)
            //->setEncryption('ssl')
            ->setUsername('contact@respawn-agency.com')
            ->setPassword('')
        ;
    }else{
        $transport = Swift_MailTransport::newInstance();
    }
    $mailer = Swift_Mailer::newInstance($transport);
    return $mailer;
};