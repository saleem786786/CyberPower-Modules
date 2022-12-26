<div class="control-group {$style.classes}">
    <div class="lu-row">
        {if $label}
            <div class="col-md-{12 - $style.width}">
                <label class="control-label">
                    {$label}
                </label>
            </div>
        {/if}

        <div class="col-md-{$style.width}">
            <div class="lu-form-group">
                <div class='lu-input-group date'>
                    <input 
                        type="date" 
                        class="lu-form-control autogenerated-datepicker" 
                        name="{$name}" 
                        value="{$value}"
                        {foreach from=$data key=dataKey item=dataValue}
                            data-{$dataKey}="{$dataValue}"
                        {/foreach}
                        style="{foreach from=$style.custom key=stl item=val}{$stl}:{$val};{/foreach}" 
                    />
                    <span class="lu-input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {if $description}
        <div class="lu-row">
            <div class="col-md-offset-{12 - $style.width} col-md-{$style.width}">
                <span class="lu-help-block">
                    {$description}
                </span>
            </div>
        </div>
    {/if}
</div>

<script type="text/javascript">
    $(document).ready(function()
    {
        $(".autogenerated-datepicker").datetimepicker({
            format: '{if $style.format}{$style.format}{else}YYYY-MM-DD{/if}'
        });
    });
</script>