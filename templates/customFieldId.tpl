{* Copyright (C) 2016-17, AGH Strategies, LLC <info@aghstrategies.com> *}
{* Licensed under the GNU Affero Public License 3.0 (see LICENSE.txt) *}

<div id="id-custom-field-id">
 <table class="form-layout-compressed">
   <tr class="crm-acl-form-block-custom_field_id">
       <td class="label">{$form.custom_field_id.label}</td>
       <td>{$form.custom_field_id.html}<br />
       {* <span class="description">{ts}Select a specific group of custom fields, OR apply this permission to ALL custom fields.{/ts}</span> *}
       </td>
   </tr>
   <tr class="crm-acl-form-block-custom_field_value">
       <td class="label">{$form.custom_field_value.label}</td>
       <td>{$form.custom_field_value.html}<br />
       {* <span class="description">{ts}Select a specific group of custom fields, OR apply this permission to ALL custom fields.{/ts}</span> *}
       </td>
   </tr>
 </table>
{* <div class="status message">{ts}NOTE: For Custom Data ACLs, the 'View' and 'Edit' operations currently do the same thing. Either option grants the right to view AND / OR edit custom data fields (in all groups, or in a specific custom data group). Neither option grants access to administration of custom data fields.{/ts}</div> *}
</div>
