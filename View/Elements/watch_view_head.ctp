                <div class="row head">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <h4><?php echo $watch['Brand']['name']; ?> 
                        <?php echo h($watch['Watch']['stockId']); ?></h4>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 right">
                        <h4><?php echo h($this->Number->currency($watch['Watch']['price'], 'USD')); ?></h4>
                    </div>
                </div>