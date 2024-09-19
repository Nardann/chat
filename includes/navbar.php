<div class="container-fluid nav_bar">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column justify-content-between text-white min-vh-100">
                
                <!-- Titre "Chat" en haut -->
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2">
                    <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline"><i class="bi bi-chat-dots"></i> Chat</span>
                    </a>
                </div>

                <!-- Liste des éléments centrée verticalement -->
                <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center">
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                        <li class="nav-item">
                            <a href="../../../index.php" class="nav-link px-0 align-middle">
                                <i class="bi bi-house"></i> <span class="ms-1 d-none d-sm-inline">Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="../../../friends/friends.php" class="nav-link px-0 align-middle">
                                <i class="bi bi-person-lines-fill"></i> <span class="ms-1 d-none d-sm-inline">Amis</span>
                            </a>
                        </li>
                        <li>
                            <a href="../../../friends/search.php" class="nav-link px-0 align-middle">
                                <i class="bi bi-person-plus"></i> <span class="ms-1 d-none d-sm-inline">Ajouter un ami</span>
                            </a>
                        </li>
                        <li>
                            <a href="../../../friends/friend_requests.php" class="nav-link px-0 align-middle">
                                <i class="bi bi-person-arms-up"></i> <span class="ms-1 d-none d-sm-inline">Demande d'amis</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Profil en bas -->
                <div class="dropdown pb-4 px-3">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-bounding-box"></i>
                        <span class="d-none d-sm-inline mx-1"><?php echo $_SESSION['username']; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="../../login/logout.php">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="col py-3">
