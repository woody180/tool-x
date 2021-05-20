<?php $this->layout('partials/template', ['title' => $title]) ?>

<?php $this->start('mainSection') ?>
<section class="uk-section">
    <div class="uk-container uk-container-small">
        <?= $description ?>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quo asperiores beatae officia accusamus quibusdam nobis ipsum repudiandae. Corporis odit voluptatem eveniet modi unde, nam fuga blanditiis harum, ut delectus optio.</p>
    
        <form action="" method="post">

            <?= $library::csrf_field(); ?>
            <input type="text" name="name"> <br/> <br/>
            <input type="text" name="username"> <br/> <br/>
            <input type="text" name="email"> <br/> <br/>
            <button type="submit">Submit</button>
        </form>
    
    </div>
</section>

<?php $this->stop() ?>