{*
 * @author Alex Sobolev <alexobolev@outlook.com>
 * @copyright 2019 Alex Sobolev
 * @license https://opensource.org/licenses/MIT The MIT License 
 *}
<!-- MyCustomContent block -->
{if isset($mycustomcontent_viewenabled) && $mycustomcontent_viewenabled}
  <div class="my-mycustomcontent-content">
    {if isset($mycustomcontent_content) && $mycustomcontent_content}
      {$mycustomcontent_content}
    {else}
      <p>{l s='Sorry, no custom content has been provided by the shop administration.'}</p>
    {/if}
  </div>
{/if}
<!-- End of MyCustomContent block -->
