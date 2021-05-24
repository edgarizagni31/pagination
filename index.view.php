<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paginación</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <form 
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])  ?>" 
            method="POST"
            class="form"
            autocomplete="off"
        >
            <input type="text" name="text" id="" placeholder="Ingrese un texto" class="form__input">
        </form>
        <table class="articles">
            <thead>
                <tr>
                    <th> ID </th>
                    <th> TEXTO </th>
                </tr>
            </thead>
            <?php
                generateArticles($articles);
            ?>
        </table>
        <section class="buttons">
            <?php
                generateList($pages, $page);
            ?>
        </section>
    </div>
</body>
</html>