<?php
class menus{
    public function main_menu(){
        ?>
        <div class="topnav">
            <a href="./">Home</a>
            <a href="about.php">About Us</a>
            <a href="">Our Projects</a>
            <a href="">Our Portfolio</a>
            <a href="">Contact Us</a>
            <?php $this->main_right_menu(); ?>
        </div>
        <?php
    }

    public function main_right_menu(){
        ?>
        <div class="topnav-right">
            <a href="">Sign Up</a>
            <a href="">Sign In</a>
        </div>
        <?php
    }
}