<!-- MyCustomContent block -->
{if isset($mycustomcontent_viewenabled) && $mycustomcontent_viewenabled}
  <div class="my-mycustomcontent-content">
    {if isset($mycustomcontent_content) && $mycustomcontent_content}
      {$mycustomcontent_content nofilter}
    {else}
      <p>{l s='Sorry, no custom content has been provided by the shop administration.'}</p>
    {/if}
  </div>
{/if}
<!-- End of MyCustomContent block -->