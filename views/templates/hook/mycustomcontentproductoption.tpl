{*
 * @author Alex Sobolev <alexobolev@outlook.com>
 * @copyright 2019 Alex Sobolev
 * @license https://opensource.org/licenses/MIT The MIT License 
 *}
<!-- MyCustomContent Product Option block -->
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <h2>{l s='My Custom Content' mod='mycustomcontent'}</h2>
                <p class="subtitle">{l s='Additional options for the products, provided by the MyCustomContent module.' mod='mycustomcontent'}</p>
            </div>
        </div>
    </div>
</div>
{* I principally object to doing form semantics
   the way PrestaShop team does with Bootstrap,
   but I do follow for the continuity's sake. *}
<div class="row">
    <div class="col-md-8 form-group">
        <div class="row">
            <div class="col-md-12">
                <div class="checkbox">
                    <label>
                        <input type="hidden" name="mcc_product_viewenabled" value="{$mycustomcontent_product_checked}">
                        <input 
                            type="checkbox" 
                            value="1" {if $mycustomcontent_product_checked}checked="checked"{/if}
                            onclick="this.previousElementSibling.value=1-this.previousElementSibling.value"> {l s='Show on product page' mod='mycustomcontent'}
                        {* The label for the checkbox on the line above. Mind the space. *}
                        {* The hacky shit for POSTing unchecked state.
                           Kudos to SO and to the original solution poster, Marcel Ennix: https://stackoverflow.com/a/25764926 *}
                    </label>
                </div>
            </div>
        </div>
        {if ! $mycustomcontent_viewenabled}
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning" role="alert">
                        <p class="alert-text">
                            {l s='These settings will be saved but the module is configured to display nothing.' mod='mycustomcontent'}
                        </p>
                    </div>
                </div>
            </div>
        {/if}
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <h4>{l s='Per-product content' mod='mycustomcontent'}</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group mb-0">
                <div class="checkbox">
                    <label>
                        <input type="hidden" name="mcc_product_overrideenabled" value="{$mycustomcontent_product_override_checked}">
                        <input 
                            type="checkbox" 
                            value="1" {if $mycustomcontent_product_override_checked}checked="checked"{/if}
                            onclick="this.previousElementSibling.value=1-this.previousElementSibling.value"> {l s='Override the global content' mod='mycustomcontent'}
                        {* See comment above. *}
                    </label>
                </div>
            </div>
        </div>
        {if ! $mycustomcontent_product_override_enabled_globally}
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning" role="alert">
                        <p class="alert-text">
                            {l s='These settings will not take effect because overriding global contents is not enabled by the module.' mod='mycustomcontent'}
                        </p>
                        <p class="alert-text">
                            {l s='This alert does not reflect changes you may have made after loading this page.' mod='mycustomcontent'}
                        </p>
                    </div>
                </div>
            </div>
        {/if}
        <div class="row">
            <div class="col-md-12">
                <fieldset class="form-group">
                    <label class="form-control-label">
                        {l s='Product\'s individual custom contents' mod='mycustomcontent'} {* The space on the next line is deliberate! *}
                         <span class="help-box"
                               data-toggle="popover"
                               data-content="{l s='Contents below will override the global setting, if the product is set to do so by the checkbox above and this is allowed in the module configuration.' mod='mycustomcontent'}">
                    </label>
                    <div class="form-control p-0">
                        <textarea class="textarea-autoresize autoload_rte" name="mcc_product_overridevalue">{$mycustomcontent_product_override_value}</textarea>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info expandable-alert" role="alert">
                    <button type="button" class="read-more btn-link" data-toggle="collapse" data-target="#mccVariablesInfo" >
                        {l s='Read more' mod='mycustomcontent'}
                    </button>
                    <p class="alert-text">
                        {l s='You can use special variables in the content text if substitution is enabled by the module.' mod='mycustomcontent'}
                    </p>
                    <div id="mccVariablesInfo" class="alert-more collapse">
                        <p class="alert-text">
                            {l s='This is a Proof-of-Concept feature allowing a (very) limited variable usage in the custom content.' mod='mycustomcontent'}
                        </p>
                        <p class="alert-text">
                            {l s='By wrapping a registered variable in curly brackets ({..}), you can make your custom content dynamic.' mod='mycustomcontent'}
                        </p>
                        <p class="alert-text mt-2">
                            {l s='The few available substitutions are:' mod='mycustomcontent'}
                            <table class="table table-sm mt-2 mb-2">
                                <tbody>
                                    {foreach from=$mycustomcontent_product_substitutions key=substitution item=description}
                                        <tr>
                                            <td><code>{$substitution}</code></td>
                                            <td>{$description}</td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </p>
                        <p class="alert-text">
                            <strong class="pr-1">{l s='Important:' mod='mycustomcontent'}</strong>
                            {l s='always check that the substitutions work as expected, as the module does not validate the variables. Misspelled variables would be visible to the customers.' mod='mycustomcontent'}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of MyCustomContent Product Option block -->
