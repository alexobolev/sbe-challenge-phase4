<!-- MyCustomContent block -->
{if isset($mycustomcontent_viewenabled) && $mycustomcontent_viewenabled}
  <div class="my-mycustomcontent-content">
    {if isset($mycustomcontent_content) && $mycustomcontent_content}
      {$mycustomcontent_content nofilter}
    {else}
      Sorry, no content provided.
    {/if}
  </div>
{/if}
<!-- End of MyCustomContent block -->