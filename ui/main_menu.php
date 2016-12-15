<?php

/*

    Main menu. If the $activeMenuItem is set (0-3), the menu item will be highlited.
    
*/

?>
<!-- main menu -->
<div class="row row-offcanvas row-offcanvas-left">
    <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
      <div class="list-group">
        <?php
          if(isset($activeMenuItem) && $activeMenuItem == 0) {
        ?>
            <a href="index.php" class="list-group-item active">O konferenci</a>    
        <?php
          } else {
        ?>
            <a href="index.php" class="list-group-item">O konferenci</a>    
        <?php
          }
        ?>
        
        <?php
          if(isset($activeMenuItem) && $activeMenuItem == 1) {
        ?>
           <!-- <a href="#" class="list-group-item active">Témata konference</a>-->
        <?php
          } else {
        ?>
           <!-- <a href="#" class="list-group-item">Témata konference</a>-->
        <?php
          }
        ?>
        
        <?php
          if(isset($activeMenuItem) && $activeMenuItem == 2) {
        ?>
            <a href="organizace.php" class="list-group-item active">Organizace</a>
        <?php
          } else {
        ?>
            <a href="organizace.php" class="list-group-item">Organizace</a>
        <?php
          }
        ?>
        
        <?php
          if(isset($activeMenuItem) && $activeMenuItem == 3) {
        ?>
            <a href="articles.php" class="list-group-item active">Příspěvky</a>    
        <?php
          } else {
        ?>
            <a href="articles.php" class="list-group-item">Příspěvky</a>
        <?php
          }
        ?>    
      </div>
    </div><!--/.sidebar-offcanvas-->
</div>