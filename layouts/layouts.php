<?php
class layouts{
    public function heading(){
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="css/style.css">
  
</head>
<body>
        <?php
    }
    public function footer(){
        ?>
 
<footer class="pt-3 mt-4 text-body-secondary border-top">
Copyright &copy; ICS <?php print date("Y"); ?>
      </footer>
    </div>
  </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
        <?php
    }
}