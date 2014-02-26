<?php

require 'vendor/autoload.php';

use classes\Model\Article; 


\Slim\Slim::registerAutoloader();

// set default conditions for route parameters
\Slim\Route::setDefaultConditions(array(
    'id' => '[0-9]{1,}',
));

// initialize app
$app = new \Slim\Slim();


// handle GET requests for /articles
$app->get('/articles', function () use ($app) {  
    // create article instance  
    $articleObj = new Article();
  
    // send response header for JSON content type
    $app->response()->header('Content-Type', 'application/json');
  
    // return JSON-encoded response body with query results
    echo $articleObj->findAllArticles();
});
    


// handle GET requests for /articles/:id
$app->get('/articles/:id', function ($id) use ($app) {
    try {
        // create article instance  
        $articleObj = new Article();
        $article = $articleObj->findByArticleId($id);
              
        if ($article) {
            $app->response()->header('Content-Type', 'application/json');
            echo $article;
        } 
        
        } catch (ResourceNotFoundException $e) {
            // return 404 server error
            $app->response()->status(404);
        } catch (Exception $e) {
            $app->response()->status(400);
            $app->response()->header('X-Status-Reason', $e->getMessage());
        }
});     
    
        
// handle POST requests to /articles
$app->post('/articles', function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);
        $articleObj = new Article();
        $result = $articleObj->save($input);
        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo $result;
    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
    }
});
        
        
// handle PUT requests to /articles/:id
$app->put('/articles/:id', function ($id) use ($app) {
    try {
        
        // get and decode JSON request body
        $request = $app->request();
        $body = $request->getBody();
        $input = json_decode($body);
        
        // create array with user input
        $articleArr = array(
            'id' => $id,
            'article_title' => (string)$input->article_title,
            'article_url' => (string)$input->article_url,
            'article_date' =>(string)$input->article_date,
            );
        
        
        $article = $articleObj = new Article();
        // store modified article
        // return JSON-encoded response body
        $app->response()->header('Content-Type', 'application/json');
        echo $article->update($articleArr);
    } catch (ResourceNotFoundException $e) {
        $app->response()->status(404);
    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
    }
});     

                
// handle DELETE requests to /articles/:id
$app->delete('/articles/:id', function ($id) use ($app) {
    try {
        $articleObj = new Article();
        $articleObj->delete($id);
    } catch (ResourceNotFoundException $e) {
        $app->response()->status(404);
    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
    }
});
            
// run
$app->run();