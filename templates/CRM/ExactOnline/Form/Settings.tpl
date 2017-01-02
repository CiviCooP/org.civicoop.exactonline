<h3>{ts}API Key{/ts}</h3>

<p>
    {ts}Enter your Exact API key details below.{/ts}<br/>
    {ts}If you do not have an API key yet, create a new one here:{/ts}
    <a href="https://apps.exactonline.com/nl/en-US/Manage" target="_blank"><strong>{ts}Manage API Keys{/ts}</strong></a>.
</p>

{foreach from=$elementNames item=elementName}
    <div class="crm-section">
        <div class="label">{$form.$elementName.label}</div>
        <div class="content">{$form.$elementName.html}</div>
        <div class="clear"></div>
    </div>
{/foreach}

<div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
