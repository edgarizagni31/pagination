<?php  
    try {
        $connect = new PDO("mysql:host=localhost;dbname=articulos", "root", "");
    }catch (PDOException $error) {
        die( $error->getMessage() );
    }

    # add text 
    $text = $_POST['text'] ?? '';
    if ( $text != '') {
        $insertText = $connect->prepare("INSERT INTO articulos (texto) VALUES (:text)");
        $insertText->bindParam(':text', $text, PDO::PARAM_STR);
        $insertText->execute();
    }

    # get number get page numbers
    $number_articles = $connect->query("SELECT COUNT(texto) as total FROM articulos;");
    $number_articles = $number_articles->fetch();
    $pages = ceil( $number_articles['total'] / 5 );
    $page = $_GET['pagina'] ?? 1;

    if ( $page > $pages || $page < $pages   ) {
        $page = 1;
    }

    $start = (int)$page * 5 - 5;

    # get articles
    $articles = $connect->prepare("SELECT * FROM articulos LIMIT :start,5;");
    $articles->bindParam(":start",$start, PDO::PARAM_INT);
    $articles->execute();
    $articles = $articles->fetchAll();

    # functions 
    function generateList( int $pages, $page ) {
        for ($i = 0; $i < $pages; $i++) { 
            if ( $i + 1 == $page ) {
                echo "<button class = 'button--active' > <a class = 'link' href = '?pagina=".($i + 1) ."'>". ($i + 1) ."</a> </button>";
            }else {
                echo "<button class = 'button' > <a class = 'link' href = '?pagina=".($i + 1) ."'>". ($i + 1) ."</a> </button>";
            }

        }
    }

    function generateArticles( array $articles ) {
        foreach ( $articles  as $article) {
            echo "<tr><td class = 'id' >". $article['id']  ."</td> <td class = 'text'>". $article['texto']  ."</td>  </tr>";
        }
    }

    require 'index.view.php';
?>