<!-- Bar de navigation utilisant Boostrap -->

<header>

    <div class="m-4">

        <nav class="navbar navbar-expand-sm navbar-dark bg-dark" style="border-radius: 10px; font-size: 100%; ">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img id="logo" src="img/Fitbud.png" width="50" height="50" class="d-inline-block align-top" alt="" style="border-radius: 20px;">
                    <?php
                    echo '<a href="exerciceRand.php" class="nav-link" style="color:#FF69B4;">Exercice aléatoire</a>';

                    echo '  <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" style="border-radius: 20px;">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                    <div id="navbarCollapse" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav" >
    
                            <li class="nav-item dropdown">
                                <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" style="color:#FFA500;">Exercices</a>
                                <div class="dropdown-menu" style="text-align:center;">
                                    <a href="add.php" class="dropdown-item">Ajouter</a>
                                </div>
                            </li>
                        </ul>';

                    echo '<ul class="nav navbar-nav">
                           <li class="nav-item dropdown" >
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" style="color:#3333ff;">Entrainements</a>
                            <div class="dropdown-menu" style="text-align:center;">
                                <a href="trainningperso.php" class="dropdown-item">Entrainement perso</a>
                                <a href="trainningperso.php?num=2" class="dropdown-item">Entrainement 1</a>
                                <a href="trainningperso.php?num=3" class="dropdown-item">Entrainement 2.0</a>

                            </div>
                                
                           </li>
                           </ul>
                                
                            <ul class="nav navbar-nav" >
                                
                             <li class="nav-item dropdown">
                                <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" style="color:#FFA500;">Aléatoire</a>
                                <div class="dropdown-menu" style="text-align:center;">
                                    <a href="entrainement.php?choix=1" class="dropdown-item" >Bras</a>
                                    <a href="entrainement.php?choix=2" class="dropdown-item">Dos</a>
                                    <a href="entrainement.php?choix=3" class="dropdown-item">Jambes</a>
                                </div>
                        </div>
                             </li>
                            </ul>';
                    ?>
            </div>
        </nav>
</header>