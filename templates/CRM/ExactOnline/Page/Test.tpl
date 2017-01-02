<p>
    OAuth token expires: {$oauth_expires|date_format:'%Y-%m-%d %H:%M:%S'}<br/>
    Authenticated as: {$me->FullName} ({$me->Email})<br/>
</p>

<h3>Items</h3>
<ul>
    {foreach from=$items item=item}
        <li>{$item->Code}: {$item->Description}</li>
    {/foreach}
</ul>

<h3>Journals</h3>
<ul>
    {foreach from=$journals item=journal}
        <li>{$journal->Description}</li>
    {/foreach}
</ul>

<h3>Contacts</h3>
<ul>
    {foreach from=$contacts item=contact}
        <li>{$contact->FullName} ({$contact->Email})</li>
    {/foreach}
</ul>

<h3>SalesInvoices + SalesInvoiceLines</h3>
<ul>
    {foreach from=$invoices item=invoice}
        <li>{$invoice->InvoiceNumber}: {$invoice->InvoiceToName}, {$invoice->Description}, {$invoice->Currency} {$invoice->AmountFC|string_format:'%.2f'}<br/>
            {foreach from=$invoice->SalesInvoiceLines item=line}
                &nbsp;&nbsp;&nbsp;{$line->ItemCode}: {$line->Description}, {$line->AmountFC|string_format:'%.2f'}, {$line->VATAmountFC|string_format:'%.2f'}<br/>
            {/foreach}
        </li>
    {/foreach}
</ul>
