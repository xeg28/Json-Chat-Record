<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="public/javascript/dropzone.min.js"></script>
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <script src="public/javascript/jquery-3.5.1.slim.min.js"></script>
    <script src="public/javascript/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="public/css/dropzone.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <link rel="stylesheet" type="text/css" href="public/css/style.css?version=<%= Common.GetVersion%" /> 
    <title><?php echo $title; ?></title>
</head>
<body>

<!-- This script prevents a user from submiting an empty form  -->
<script>
    document.addEventListener('submit', function(event) {
        var target = event.target;
        var elements = target.elements;
        for(let i = 0; i < elements.length; i++) {
            var element = elements[i];
            var value = element.value.trim();
            if(value === '' && element.classList.contains('field')) {
                event.preventDefault();
            }
        }     
        return;
    });

</script>

<script src='public/javascript/views/upload.js'></script>
<script src='public/javascript/views/home.js'></script>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto px-4">
            <li class="nav-item <?php if ($title == 'Home') {
                                    echo 'active';
                                } ?>">
                <a class="nav-link" href="<?= base_url('/home') ?>">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link upload-btn" href="#">Upload</a>
            </li>
        </ul>

        <form action="<?= base_url() ?>/search" method="get">
                <div class="input-group px-5">
                    <input type="search" class="form-control field" name="query" placeholder="Search..." aria-label="Search">
                    <div class="input-group-append">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        
    </div>
</nav>

