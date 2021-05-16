<?php $this->layout("partials/restLayout", ["title => $title"]) ?>

<?php $this->start("mainSection") ?>
<div class="uk-padding">

<h1>New Articles</h1>
<hr class="uk-divider-small">

<div class="uk-margin">
    <form enctype="multipart/form-data" action="<?= URLROOT . "/api/blog/articles" ?>" method="POST" class="uk-child-width-1-2@s" uk-grid>

        <div>
            <label for="" class="uk-form-label">Articles title</label>
            <input type="text" name="title" class="uk-input" value="">
        </div>
        
        <div>
            <label for="" class="uk-form-label">Articles url</label>
            <input type="text" name="url" class="uk-input" value="">
        </div>

        <div class="uk-width-1-1 uk-flex">
            <div class="uk-margin-right">
                <label for="" class="uk-form-label">Thubmnail</label>
                <input type="hidden" name="thumbnail" class="uk-input" value="">
                
                <div style="border: 1px solid #e5e5e5; height: 85px">
                    <img style="object-fit: cover;" width="140" class="uk-display-block uk-height-1-1" src="<?= PUBLIC_DIR . "/images/not-found.png" ?>" alt="">
                </div>
            </div>

            <div class="uk-width-expand uk-flex uk-flex-between uk-flex-column">
                <label for="" class="uk-form-label">Upload thubmnail</label>
                <div class="js-upload uk-placeholder uk-text-center uk-margin-remove">
                    <span uk-icon="icon: cloud-upload"></span>
                    <span class="uk-text-middle">Attach binaries by dropping them here or</span>
                    <div uk-form-custom>
                        <input type="hidden" name="thumbnail">
                        <span id="open-file-manager" class="uk-link">selecting one</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="uk-width-1-1">
            <button class="uk-button uk-button-primary uk-width-1-1 uk-border-rounded" type="submit">Submit new Articles</button>
        </div>
    
    </form>
</div>

</div>

<?php $this->stop() ?>
        