<!-- MyCustomContent Product Option block -->
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <h2>{l s='My Custom Content' mod='mycustomcontent'}</h2>
                <p class="subtitle">{l s='Additional options for the products, provided by MyCustomContent module.' mod='mycustomcontent'}</p>
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
                            onclick="this.previousElementSibling.value=1-this.previousElementSibling.value"> {l s='Show on product page'}
                        {* The label for the checkbox on the line above. Mind the space. *}
                        {* The hacky shit for POSTing unchecked state.
                           Kudos to SO and to the original solution poster, Marcel Ennix: https://stackoverflow.com/a/25764926 *}
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8 form-group">
        <div class="row">
            <div class="col-md-12">
                <h4>{l s='Debug'}</h4>
                <p class="subtitle">This is not updated on F5 / page reload.</p>
                <div><p>{$mycustomcontent_product_checked}</p></div>
                <div>{$mycustomcontent_product_debug nofilter}</div>
            </div>
        </div>
    </div>
</div>
<!-- End of MyCustomContent Product Option block -->
