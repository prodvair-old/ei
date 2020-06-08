/**
 * Search on demand.
 * Loading the next pool of models.
 * 
 * @param string load_more_url
 *   
 * ```php
 *       <div class='box model-index table-responsive'>
 *           <div class='box-header'>
 *               <?= Html::a(Yii::$app->params['icons']['plus'] . ' ' . Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']) ?>
 *
 *               <?= Html::submitButton(Yii::t('app', 'Find'), ['class' => 'btn btn-primary search-submit']) ?>
 *           </div>
 *
 *           <div class='box-body'>
 *               <?= GridView::widget([
 *                   'id' => 'model-grid',
 *                   'dataProvider' => $dataProvider,
 *                   'filterModel' => $searchModel,
 *                   'filterOnFocusOut' => false,
 *                   'layout' => "{items}\n{summary}",
 *                   'options' => ['class' => false],
 *                   'columns' => $columns,
 *               ]); ?>
 *           </div>
 *
 *           <div class='box-footer'>
 *               <p class='model-spinner' style='display: none;'>
 *                   <i class='fa fa-spinner fa-spin fa-fw'></i>
 *               </p>
 *
 *               <?= Html::submitButton(Yii::t('app', 'More'), [
 *                   'class' => 'btn btn-primary load-more',
 *                   'data-offset' => $dataProvider->getCount(),
 *               ]) ?>
 *           </div>
 *       </div>
 * ```
 */
$(document).ready(function() {
    /**
     * Submit finding criteria.
     */
    $('body').on('click', '.btn.search-submit', function(){ $("#model-grid").yiiGridView("applyFilter"); });

    /**
     * Load more models and add them to the tail of a table with prevoausly loaded models.
     */
    $('body').on('click', '.btn.load-more',  function(){
        var that = $(this);
        var offset = that.attr('data-offset');
        var model_index = $('.model-index');
        var model_grid = model_index.find('#model-grid');
        var model_table_body = model_grid.find('table tbody');
        var summary = model_grid.find('.summary b');
        var filter = model_grid.find('.filters');
        var spinner = model_index.find('.model-spinner');
        
        spinner.show();

        $.ajax({
            url: load_more_url,
            data: (filter.find('.form-control').serialize() + '&offset=' + offset),
            success: function(response) {
                if (response.count) {
                    model_table_body.append(response.content);
                    summary.text(Number(summary.text()) + Number(response.count));
                    that.attr('data-offset', Number(offset) + Number(response.count));
                } else
                    that.attr('disabled','disabled');
                spinner.hide();
            }
        });
    });
});
