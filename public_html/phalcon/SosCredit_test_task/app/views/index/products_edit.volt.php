
<?php echo $this->tag->form(array('products/save', 'role' => 'form')); ?>

    <ul class="pager">
        <li class="previous pull-left">
            <?php echo $this->tag->linkTo(array('products', '&larr; Go Back')); ?>
        </li>
        <li class="pull-right">
            <?php echo $this->tag->submitButton(array('Save', 'class' => 'btn btn-success')); ?>
        </li>
    </ul>

    <?php echo $this->getContent(); ?>

    <h2>Edit products</h2>

    <fieldset>

        <?php foreach ($form as $element) { ?>
            <?php if (is_a($element, 'Phalcon\Forms\Element\Hidden')) { ?>
                <?php echo $element; ?>
            <?php } else { ?>
                <div class="form-group">
                    <?php echo $element->label(); ?>
                    <?php echo $element->render(array('class' => 'form-control')); ?>
                </div>
            <?php } ?>
        <?php } ?>

    </fieldset>

</form>
