<?php 

use App\Entity\User;
use Slim\Views\Twig;
use Slim\Psr7\Request;
use App\Entity\Article;
use Slim\Psr7\Response;
use App\Entity\Category;
use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;
require_once dirname(__DIR__)."/bootstrap.php";


$app = AppFactory::create();

$twig = Twig::create(dirname(__DIR__).'/templates', ['cache' => false]);

$app->add(TwigMiddleware::create($app, $twig));
// Add error middleware
$app->addErrorMiddleware(true, true, true);

//getParams function

function getParams(){
    
    $params = isset($_SERVER["QUERY_STRING"]) ? explode("&", $_SERVER["QUERY_STRING"]):[];
    $result = [];
    foreach ($params as $param) {
        # code...
        $param = explode("=", $param);

        $result[$param[0]] = $param [1];
    }

    return $result;
}

$categories = $entityManager->getRepository(Category::class)->findAll();
// Add routes
$app->get('/', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'home/home.html.twig', [
        'name' => "Test",
        'categories' => $GLOBALS["categories"]
    ]);
});
$app->get('/articles', function (Request $request, Response $response, $args) {
   /// call get params functions
    $articleRepo = $GLOBALS["entityManager"]->getRepository(Article::class);
    $view = Twig::fromRequest($request);
    $numberPerPage = 100;

    $result = getParams();

    $page = isset($result["page"]) ? $result["page"]:1;
    if ($page > 1) {
        $previous = $page - 1;
        $next = $page + 1;
    }else{
        $previous = null;
        $next = $page + 1;
    }


    $articleCount = $articleRepo->getLength();
    if ($next > $articleCount / $numberPerPage) {
        # code...
        $next = null;
    }
   // var_dump();
    return $view->render($response, 'articles/article.html.twig', [
        'data' => [
            "current" => $page,
            "next" => $next,
            "previous" => $previous,
            "articles" => $articleRepo->getArticlesByPage($numberPerPage, $page)
        ],
        'articleCount' => $articleCount,
        'categories' => $GLOBALS["categories"]
    ]);
});
$app->get('/single-article/{slug}', function (Request $request, Response $response, $args) {
    $articleRepo = $GLOBALS["entityManager"]->getRepository(Article::class);
    $view = Twig::fromRequest($request);
    $slug = $args["slug"];
    $article = $articleRepo->findOneBySlug($slug);

    if(!$article){
        // ERROR
        return $view->render($response, 'errors/error-404.html.twig');
    }

    return $view->render($response, 'single/single_article.html.twig', [
        'article' => $article,
        'categories' => $GLOBALS["categories"]
    ]);
});
$app->get('/article/by/category/{slug}', function (Request $request, Response $response, $args) {
    $categoriesRepo = $GLOBALS["entityManager"]->getRepository(Category::class);
    $articleRepo = $GLOBALS["entityManager"]->getRepository(Article::class);

    $numberPerPage = 6;
    $view = Twig::fromRequest($request);
    $slug = $args["slug"];
    $category = $categoriesRepo->findOneBySlug($slug);
    if(!$category){
        // ERROR
        return $view->render($response, 'errors/error-404.html.twig');
    }

    $articles = $category->getArticles()->getValues();


    /** QUERY */
   
    $result = getParams();

    $page = isset($result["page"]) ? $result["page"]:1;
    if ($page > 1) {
        $previous = $page - 1;
        $next = $page + 1;
    }else{
        $previous = null;
        $next = $page + 1;
    }


    if ($next > count($articles) / $numberPerPage) {
        # code...
        $next = null;
    }

    /** END QUERY */
    //$articles = $category->getArticles();
    
    $start = ($page-1)*$numberPerPage;

    return $view->render($response, 'articles/article.html.twig', [
        'category'=> $category,
        'categories' => $GLOBALS["categories"],
        'articles' => $articles,
        'data' => [
            "current" => $page,
            "next" => $next,
            "previous" => $previous,
            "articles" => array_slice($articles, $start, $numberPerPage)
        ]
    ]);
});

$app->run();