<div class="items view">
	<h2><?php  echo h($item['Item']['name']); ?></h2>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <?php if (!empty($item['Item']['filenameLarge'])): ?>
                <?php echo $this->Html->image($item['Item']['filenameLarge']); ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 info">
            <div class="info-inner">
                <div class="row body">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 description">
                        <?php echo $item['Item']['description']; ?>
                    </div>
                </div>
                <div class="row">
                    <p class="text-center bottom">
                        <?= $this->Form->create('Item'); ?>
                        <?= $this->Form->input('quantity', [
                            'class' => 'form-control',
                            'options' => $options,
                        ]) ?>
                        <?= $this->Form->submit('Add to Cart', [
                                'class' => 'btn btn-gold btn-lg',
                            ]
                        ); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
