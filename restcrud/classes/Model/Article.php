<?php

namespace classes\Model;

use RedBean_Facade as R;  
use classes\Exception\ArticleException;

class Article 
{   
    /*
     * define Article Constructor
     * initilaize redbean database object
     */
    public function __construct() 
    {
        // set up database connection
        R::setup('mysql:host=localhost;dbname=restcrud','XXXX','XXX');
        R::freeze(true);
    }
    
    /*
     * deifne findAllArticles
     * takes no argument
     * returns all the articles
     */
    public function findAllArticles() 
    {
        // query database for all articles
        $articles = R::find( 'articles' );
        return json_encode(R::exportAll($articles));
    }

    /*
     * define findByArticleId function
     * takes one argument
     * $id : id of the article 
     * returns associative article
     */
    public function findByArticleId($id)
    {
        // query database for single article
        $article = R::findOne('articles', 'id=?', array($id));
        if ($article) {
            // if found, return JSON response
            return json_encode(R::exportAll($article));
        } else {
            // else throw exception
            throw new ResourceNotFoundException();
        }    
          
    }
      
    /*
     * define save function
     * takes one argument
     * $params an array holds an article
     * save an article to database
     */  
    public function save($paramArr) {
        try 
        {
            // store article record
            $article = R::dispense('articles');
            $article->article_title = (string)$paramArr['article_title'];
            $article->article_url = (string)$paramArr['article_url'];
            $article->article_date = (string)$paramArr['article_date'];
            $id = R::store($article);
            return $id;
        } catch (Exception $e) 
        {
            throw new Exception();
        }             
    }
      
    /*
     * define function date
     * takes one argument
     * $id : id of the article
     * updates an article by id
     */
    public function update($paramArr) 
    {
        // query database for single article
        $article = R::findOne('articles', 'id=?', array($paramArr['id']));
        // store modified article
        // return JSON-encoded response body
        if ($article) 
        {
            $article->article_title = $paramArr['article_title'];
            $article->article_url = $paramArr['article_url'];
            $article->article_date = $paramArr['article_date'];
            R::store($article);
            return  json_encode(R::exportAll($article));
        } else {
            throw new ResourceNotFoundException();
        }  
    }
    
    /*
     * define delete function
     * takes one argument
     * $id : id of the article
     * delete an article by id
     */
    public function delete($id){
        try {
            // query database for article
            $article = R::findOne('articles', 'id=?', array($id));
            R::trash($article); 
        } catch (ResourceNotFoundException $e) {
            throw new ResourceNotFoundException();
        } catch (Exception $e) {
            throw new Exception(); 
        }   
    }  
}// End of Article Class
?>
