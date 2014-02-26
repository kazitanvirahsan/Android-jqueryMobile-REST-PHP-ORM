<?php

use classes\Model\Article; 
//require 'C:/Apache24/htdocs/restcrud/classes/Model/Article.php';
use RedBean_Facade as R;

class Articletest extends PHPUnit_Framework_TestCase
{ 
    protected function setUp()
    {
        // set up database connection
        R::setup('mysql:host=localhost;dbname=restcrud','root','sa');
        R::freeze(true);
    }
    
    public function testFindAllArticles() 
    {
        $articleObj = new Article();
        $articles = $articleObj->findAllArticles();
        $articlesCompare = json_encode(R::exportAll(R::find( 'articles' )));
        $this->assertSame( $articles, $articlesCompare );
    }
    
    public function testSelectAlbumById()
    {   
        // query database for single article
        $article = R::findOne('articles', 'id=?', array(1));
        // if found, return JSON response
        $articlesCompare = json_encode(R::exportAll($article));
            
        $articleObj = new Article();
        $article = $articleObj->findByArticleId(1);
        // query database for single article
        $this->assertSame($article ,  $articlesCompare);
    }
    
    
    public function testCreateNewAlbum()
    {
        $arr = array(
            'article_title' => 'This is a test',
            'article_url' => 'www.test.com',
            'article_date' => '2012-07-10 00:00:00');
        
        $articleObj = new Article();
        $newlySavedArticleId = $articleObj->save($arr);
        
        //echo 'Newly Saved is' . $newlySavedArticleId;
        
        // query database for single article
        $articleFromDb = R::findOne('articles', 'id=?', array($newlySavedArticleId));
        
        $this->assertSame($arr ['article_title'],  $articleFromDb['article_title']);
        $this->assertSame($arr ['article_url'],  $articleFromDb['article_url']);
        $this->assertSame($arr ['article_date'],  $articleFromDb['article_date']);
        // now remove it 
        R::trash($articleFromDb); 
    }
    
    public function testDeleteAlbumById()
    {
        $arr = array(
            'article_title' => 'This is a test',
            'article_url' => 'www.test.com',
            'article_date' => '2012-07-10 00:00:00'
        );
        
         $articleObj = new Article();
         $newlySavedArticleId = $articleObj->save($arr);
         $articleObj->delete($newlySavedArticleId);
    }
    
    public function testUpdateAlbumById()
    {
        $arr = array(
            'article_title' => 'This is a test',
            'article_url' => 'www.test.com',
            'article_date' => '2012-07-10 00:00:00');
        
        $articleObj = new Article();
        // add new article
        $newlySavedArticleId = $articleObj->save($arr);

        //fetch article by it
        $article = R::findOne('articles', 'id=?', array($newlySavedArticleId));

        //modify article
        $arr = array(
            'id' => $newlySavedArticleId,
            'article_title' => 'This article is modified',
            'article_url' => 'www.test.com',
            'article_date' => '2012-07-10 00:00:00');
        
        $articleObj->update($arr);
        
        //fetch article again
        $article = R::findOne('articles', 'id=?', array($newlySavedArticleId));
        
        //compare the value
        $this->assertSame($arr['article_title'],  $article->article_title);
        
        //remove the article
        $articleObj->delete($newlySavedArticleId);
    }  
        
}