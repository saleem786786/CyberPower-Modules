<input type="hidden" name="{$rawObject->getName()}" value="{$rawObject->getValue()}" {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach} />