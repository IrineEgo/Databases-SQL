<?php
$host = 'localhost';
$dbname = 'global';
$dbuser = 'iegorenkova';
$dbpassword = 'neto1897';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpassword, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $sqlQuery = "SELECT * FROM `books`";
    $isbn = !empty($_GET['isbn']) ? '%' . trim($_GET['isbn']) . '%' : null;
    $author = !empty($_GET['author']) ? '%' . trim($_GET['author']) . '%' : null;
    $name = !empty($_GET['name']) ? '%' . trim($_GET['name']) . '%' : null;
    
    if (!$isbn && !$author && !$name) {
        $statement = $pdo->prepare($sqlQuery);
        $statement->execute();
    } else {
        $sqlQuery = "SELECT * FROM `books` WHERE isbn LIKE ? OR author LIKE ? OR `name` LIKE ?";
        $statement = $pdo->prepare($sqlQuery);
        $statement->execute([$isbn, $author, $name]);
    }
} catch (PDOException $e) {
    die($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>Библиотека книг</title>
    <style type="text/css">	
      a {
        text-decoration: none;
		color: black;
      }	
      form {
        margin-bottom: 20px;
      }
	  table {
        border-spacing: 0;
        border-collapse: collapse;
      }
	  table td, table th {
        border: 1px solid lightgrey;
        padding: 5px;
      }
	  
      tr:first-child {
        background: whitesmoke;
		font-weight: bold;
      }
	  ul>li {
        list-style-type: none;
		color: red;
      }
	  
	</style> 
  </head>
  <body>
    <h1>Библиотека успешного человека</h1>
    <section>
      <div class="form">
        <form method="GET">
            <input type="text" name="isbn" placeholder="ISBN" id="isbn" value="<?php if (!empty($isbn)) echo trim($isbn, '%') ?>">
			<input type="text" name="name" placeholder="Название книги" id="name" value="<?php if (!empty($name)) echo trim($name, '%') ?>">
            <input type="text" name="author" placeholder="Автор книги" id="author" value="<?php if (!empty($author)) echo trim($author, '%') ?>">            
            <input type="submit" value="ПОИСК">
        </form>
      </div>

      <?php if ($statement->rowCount() === 0): ?>
        <ul>
            <?php if (!empty($isbn) && $statement->rowCount() === 0): ?>
                <li><h3>По фильтру ISBN ничего не найдено</h3></li>
            <?php endif; ?>
            <?php if (!empty($author) && $statement->rowCount() === 0): ?>
                <li><h3>По фильтру "Автор книги" ничего не найдено</h3></li>
            <?php endif; ?>
            <?php if (!empty($name) && $statement->rowCount() === 0): ?>
                <li><h3>По фильтру "Название книги" ничего не найдено</h3></li>
            <?php endif; ?>
        </ul>
		<br><br><a href="index.php"><b>&laquo;  НАЗАД</b></a>
      <?php endif; ?>

      <?php if ($statement->rowCount() !== 0): ?>
        <table>
            <tr>
                <td>Название</td>
                <td>Автор</td>
                <td>Год</td>
                <td>Жанр</td>
                <td>ISBN</td>
            </tr>
            <?php foreach ($statement as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']) ?></td>
                    <td><?php echo htmlspecialchars($row['author']) ?></td>
                    <td><?php echo htmlspecialchars($row['year']) ?></td>
                    <td><?php echo htmlspecialchars($row['genre']) ?></td>
                    <td><?php echo htmlspecialchars($row['isbn']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
      <?php endif; ?>
    </section>
  </body>
</html>
