
<?php $this->layout("partials/restLayout", ["title => $title"]) ?>

<?php $this->start("mainSection") ?>
    <div class="uk-padding">
        <div class="uk-margin-medium-bottom">
            <h1>Articles list</h1>
            <hr class="uk-divider-small">
        </div>

        <form action="<?= URLROOT . "/Articles/1" ?>" method="POST" class="uk-overflow-auto">
            
            <input type="hidden" name="_method" value="delete">

            <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">

                <thead>
                    <tr>
                        <th class="uk-table-shrink">Thumb</th>
                        <th class="uk-table-expand">Name</th>
                        <th class="uk-table-shrink uk-text-truncate">Sort</th>
                        <th class="uk-table-shrink uk-text-truncate">Open</th>
                        <th class="uk-table-shrink uk-text-truncate">Edit</th>
                        <th class="uk-table-shrink">Delete</th>
                    </tr>
                </thead>

                <?php foreach ($Articles as $item): ?>
                    <tr>
                        <td href="<?= URLROOT . "/Articles/{$item->id}" ?>" class="uk-border-circle uk-display-block uk-overflow-hidden uk-border-circle uk-margin-right">
                            <a class="uk-display-block uk-border-circle uk-overflow-hidden" style="height: 60px; width: 60px;" href="<?= URLROOT . "/Articles/{$item->id}" ?>">
                                <?php if ($item->thumbnail): ?>
                                    <img class="uk-height-1-1 uk-width-1-1 uk-display-block" src="<?= PUBLIC_DIR . "/tinyeditor/filemanager/files/$item->thumbnail" ?>" alt="<?= $item->title ?>">
                                <?php else: ?>
                                    <img class="uk-height-1-1 uk-width-1-1 uk-display-block" src="<?= PUBLIC_DIR . "/images/not-found.png" ?>" alt="<?= $item->title ?>">
                                <?php endif; ?>
                            </a>
                        </td>
                        
                        <td class="uk-link-reset" href="<?= URLROOT . "/Articles/$item->id" ?>">
                            <a href="<?= URLROOT . "/Articles/{$item->id}" ?>">
                                <?= $item->title ?>
                            </a>
                        </td>

                        <td> <input style="width: 60px" class="Articles-ordering uk-input" name="ordering" type="number" value="<?= $item->ordering ?>" /> </td>
                        <td>
                            <?php if ($item->url): ?>
                                <a href="<?= $item->url ?>" style="background-color: #d8d8d8; color: black;" class="uk-icon-button uk-margin-right" href="<?= URLROOT . '/Articles/' . $item->id . '/edit' ?>" uk-icon="icon: link; ratio: .7"> </a>
                            <?php else: ?>
                                <a href="#" style="background-color: #d8d8d8; color: black; opacity: .5;" class="uk-disabled uk-icon-button uk-margin-right" href="<?= URLROOT . '/Articles/' . $item->id . '/edit' ?>" uk-icon="icon: link; ratio: .7"> </a>
                            <?php endif; ?>
                        </td>
                        <td> <a href="<?= URLROOT . "/Articles/{$item->id}/edit" ?>" style="background-color: #d8d8d8; color: black;" class="uk-icon-button uk-margin-right" href="<?= URLROOT . '/Articles/' . $item->id . '/edit' ?>" uk-icon="icon: pencil; ratio: .7"> </a></td>
                        <td><input class="uk-padding-small uk-border-circle uk-checkbox uk-icon-button" type="checkbox" name="delete[]" value="<?= $item->id ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="uk-flex uk-flex-right">
                <button class="uk-button uk-button-primary uk-border-rounded" type="submit">Delete selected items</button>
            </div>
        </form>

        <?php if ($paging): ?>
        <div class="uk-margin-top uk-flex uk-flex-center">
            <?= $paging ?>
        </div>
        <?php endif; ?>
    </div>

<?php $this->stop() ?>
        