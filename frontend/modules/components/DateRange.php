<?php


namespace frontend\modules\components;

use kartik\daterange\DateRangePicker;
use kartik\daterange\DateRangePickerAsset;
use kartik\daterange\LanguageAsset;
use kartik\daterange\MomentAsset;
use yii\helpers\ArrayHelper;

class DateRange extends DateRangePicker
{

    public function registerAssets()
    {
        $view = $this->getView();
        MomentAsset::register($view);
        $input = 'jQuery("#' . $this->options[ 'id' ] . '")';
        $id = $input;
        if ($this->hideInput) {
            $id = 'jQuery("#' . $this->containerOptions[ 'id' ] . '")';
        }
        if (!empty($this->_langFile)) {
            LanguageAsset::register($view)->js[] = $this->_langFile;
        }
        DateRangePickerAsset::register($view);
        $rangeJs = '';
        if (empty($this->callback)) {
            $val = "start.format('{$this->_format}') + '{$this->_separator}' + end.format('{$this->_format}')";
            if (ArrayHelper::getValue($this->pluginOptions, 'singleDatePicker', false)) {
                $val = "start.format('{$this->_format}')";
            }
            $rangeJs = $this->getRangeJs('start') . $this->getRangeJs('end');
            $change = "{$input}.val(val).trigger('change');{$rangeJs}";
            if ($this->presetDropdown) {
                $id = "{$id}.find('.kv-drp-dropdown')";
            }
            if ($this->hideInput) {
                $script = "var val={$val};{$id}.find('.range-value').val(val);{$change}";
            } elseif ($this->useWithAddon) {
                $id = "{$input}.closest('.input-group')";
                $script = "var val={$val};{$change}";
            } elseif (!$this->autoUpdateOnInit) {
                $script = "var val={$val};{$change}";
            } else {
                $this->registerPlugin($this->pluginName, $id);
                return;
            }
            $this->callback = "function(start,end,label){{$script}}";
        }
        $nowFrom = "moment().startOf('day').format('{$this->_format}')";
        $nowTo = "moment().format('{$this->_format}')";
        // parse input change correctly when range input value is cleared
        $js = <<< JS
{$input}.off('change.kvdrp').on('change.kvdrp', function(e) {
    var drp = {$id}.data('{$this->pluginName}'), fm, to;
    if ($(this).val() || !drp) {
        return;
    }
    fm = {$nowFrom} || '';
    to = {$nowTo} || '';
    drp.setStartDate(fm);
    drp.setEndDate(to);
    {$rangeJs}
});

 {$id}.find('.kv-clear').on('click', function(e) {
        console.log('qqqqwww');
        e.stopPropagation();
        {$id}.find('.range-value').val('');
        {$input}.val('').trigger('change').trigger('cancel.daterangepicker');
    });

JS;
        if ($this->presetDropdown) {
            $js .= <<< JS
    {$id}.on('apply.daterangepicker', function() {
        var drp = {$id}.data('{$this->pluginName}'), newValue = drp.startDate.format(drp.locale.format);
        if (!drp.singleDatePicker) {
            newValue += drp.locale.separator + drp.endDate.format(drp.locale.format);
        }
        if (newValue !== {$input}.val()) {
            {$input}.val(newValue).trigger('change');
        }
    });            
    {$id}.find('.range-value').attr('placeholder', {$input}.attr('placeholder'));
    {$id}.find('.kv-clear').on('click', function(e) {
        console.log('qqqqwww');
        e.stopPropagation();
        {$id}.find('.range-value').val('');
        {$input}.val('').trigger('change').trigger('cancel.daterangepicker');
    });
JS;
        }
        $view->registerJs($js);
        $this->registerPlugin($this->pluginName, $id, null, $this->callback);
    }

}