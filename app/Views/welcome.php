<?php $this->layout('partials/template', ['title' => $title]) ?>

<?php $this->start('mainSection') ?>
<section class="uk-section">
    <div class="uk-container uk-container-small">
        <?= $description ?>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quo asperiores beatae officia accusamus quibusdam nobis ipsum repudiandae. Corporis odit voluptatem eveniet modi unde, nam fuga blanditiis harum, ut delectus optio.</p>    
    
        
        <form action="<?= baseUrl('create') ?>" method="post">
        
            <?= csrf_field() ?>

            <div>
                <p><input type="text" name="one"></p>
            </div>

            <div>        
                <p><input type="text" name="two"></p>
            </div>

            <button type="submit">Submit</button>

        </form>
    </div>
</section>

<?php $this->stop() ?>