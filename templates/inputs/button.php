<button 
<?php
if(!empty($args['id']))
    echo "id=$args[id] ";

if(!empty($args['name']))
    echo "name=$args[name] ";

if(!empty($args['class']))
    echo "class=$args[class] ";

if(!empty($args['autofocus']))
    echo 'autofocus ';

if(!empty($args['disableAutocomplete']))
    echo 'autocomplete="off" ';

if(!empty($args['disabled']))
    echo "disabled ";

if(!empty($args['form']))
    echo "form=$args[form] ";

if(!empty($args['formaction']))
    echo "formaction=$args[formaction] ";

if(!empty($args['formenctype']))
    echo "formenctype=$args[formenctype] ";

if(!empty($args['formmethod']))
    echo "formmethod=$args[formmethod] ";

if(!empty($args['formnovalidate']))
    echo "formnovalidate ";

if(!empty($args['formtarget']))
    echo "formtarget=$args[formtarget] ";

if(!empty($args['type']))
    echo "type=$args[type] ";

if(!empty($args['value']))
    echo "value=$args[value] ";
?>
>
<?php echo $args['text']; ?>
</button>