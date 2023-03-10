{**********************************************************************
* ModuleFramework product developed. (2017-10-30)
* *
*
*  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
*  CONTACT                        ->       contact@modulesgarden.com
*
*
* This software is furnished under a license and may be used and copied
* only  in  accordance  with  the  terms  of such  license and with the
* inclusion of the above copyright notice.  This software  or any other
* copies thereof may not be provided or otherwise made available to any
* other person.  No title to and  ownership of the  software is  hereby
* transferred.
*
*
**********************************************************************}

{**
* @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
*}
<div class="lu-form-group">
    <label>
    <span class="lu-form-text">
        {if $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
        {if $rawObject->getDescription()}
            <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper lu-tooltip "></i>
        {/if}
    </span>
        <div class="lu-switch">
            <input type="checkbox" class="lu-switch__checkbox" name="{$rawObject->getName()}"
                   namespace="{$namespace}" index="{$rawObject->getIndex()}" id="{$rawObject->getId()}"
            {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
            value="{$rawObject->getValue()}" {if $rawObject->getValue() === 'on'}checked{/if}
            >
            <span class="lu-switch__container"><span class="lu-switch__handle"></span></span>
        </div>
    </label>
</div>
