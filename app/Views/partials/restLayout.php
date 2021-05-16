<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.6.21/dist/css/uikit.min.css" />


    <!-- jQuery JS -->
    <script src="<?= PUBLIC_DIR ?>/filemanager/extend/js/jquery-3.6.0.min.js"></script>
    <script src="<?= PUBLIC_DIR ?>/filemanager/extend/js/jquery-ui.js"></script>

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.21/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.21/dist/js/uikit-icons.min.js"></script>

    <?= $this->section("styles"); ?>
    
    <title><?= $title ?? APPNAME; ?></title>
</head>
<body style="background-color: #f4f7ff;">
    <div class="uk-grid-collapse" uk-grid>

        <!-- Sidebar section -->
        <div class="uk-width-1-6@xl uk-width-1-5@l uk-width-1-4@s uk-background-secondary" uk-height-viewport="offset-top: true">
            <div class="uk-padding uk-light" uk-sticky>
                
                <!-- Here comes sidebar navigation -->
                <a href="#">Sidebar nav</a>
                
            </div>
        </div>

        <div class="uk-width-expand">
        <header class="uk-background-default uk-box-shadow-small uk-padding uk-padding-remove-bottom uk-padding-remove-top uk-flex uk-flex-between uk-flex-middle" style="height: 60px;" uk-sticky>
        
            <form class="uk-search uk-search-navbar">
                <span uk-search-icon></span>
                <input class="uk-search-input" type="search" placeholder="Search">
            </form>
        
        
            <div class="uk-flex">
                <a href="<?= URLROOT . "/Articles/new" ?>" class="uk-icon-button uk-background-primary uk-light" uk-icon="icon: plus; ratio: .8;"></a>
            </div>
        
        </header>
            
            <?= $this->section("mainSection"); ?>
        </div>

    </div>
</body>
</html>
