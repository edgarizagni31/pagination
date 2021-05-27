<?php

    # composer 
    require 'vendor/autoload.php';
    use Dotenv\Dotenv;

    try {
        # load dontenv
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        extract($_ENV);

        # load db
        $connect = new PDO("mysql:host=$DDBB_HOST;dbname=$DDBB_NAME", $DDBB_USER, $DDBB_PASS);
    }catch (PDOException $error) {
        die();
    }

    # add text 
    if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
        extract($_POST);

        # clean text
        $text = filter_var($text, FILTER_SANITIZE_STRING);
        $text = stripslashes($text);
        $text = trim($text);

        # insert text in db
        $insert_text = $connect->prepare("INSERT INTO articulos (texto) VALUES (:text)");
        $insert_text->bindParam(':text', $text, PDO::PARAM_STR);
        $res = $insert_text->execute();

        if ( $res === false ) {
            die();
        }

        # unset query
        unset($insert_text);
    }

    # get page numbers
    $get_number_articles = $connect->query("SELECT COUNT(texto) as total FROM articulos;");
    $number_articles = $get_number_articles->fetch();

    # unset query 
    unset($get_number_articles);

    if ( $number_articles === false ) {
        $number_articles = 1;
    }

    $pages = ceil( $number_articles['total'] / 5 );
    $page_number = $_GET['pagina'] ?? 1;

    if ( $page_number < 1 || $page_number > $pages ) {
        $page_number = 1;
    }

    $start = (int) ($page_number * 5 - 5);

    # get articles
    $get_articles = $connect->prepare("SELECT * FROM articulos LIMIT :start,5;");
    $get_articles->bindParam(":start",$start, PDO::PARAM_INT);
    $res = $get_articles->execute();

    if ( $res === false ) {
        $articles = [];
    } else  {
        $articles = $get_articles->fetchAll();
    }

    # unset query
    unset($get_articles);

    # functions 
    function generateList( int $pages, $page ) {
        for ($i = 0; $i < $pages; $i++) { 
            if ( $i + 1 == $page ) {
                echo "<button class = 'button--active' ><a class = 'link' href = '?pagina=".($i + 1) ."'>". ($i + 1) ."</a></button>";
            }else {
                echo "<button class = 'button' ><a class = 'link' href = '?pagina=".($i + 1) ."'>". ($i + 1) ."</a></button>";
            }

        }
    }

    function generateArticles( array $articles ) {
        foreach ( $articles  as $article) {
            echo "<tr><td class = 'id' >". $article['id']  ."</td><td class = 'text'>". $article['texto']  ."</td></tr>";
        }
    }

    # close db
    unset($connect);

    require 'index.view.php';
?>