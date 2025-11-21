<h4>Example Page With Headers</h4>

<p><a href="<?=$module->getUrl('public-page.php', true)?>">Click here</a> for an example of a NOAUTH page.</p>

<button id='jquery-post'>Test jQuery post() (with automatically added CSRF tokens)</button>

<p id='language-example'></p>

<?php
$module->setupExampleActions();
$module->tt_addToJavascriptModuleObject('example_1', 'This is an example of a javascript language string.');
?>

<script>
    (function(){
        var module = <?=$module->getJavascriptModuleObjectName()?>;

        $('button#jquery-post').click(function(){ // move this?
            $.post(<?=json_encode($module->getUrl('ajax-test.php'))?>, '', function(data){
                if(data === 'success'){
                    alert('The request completed successfully.')
                }
                else{
                    alert('The request failed!')
                }
            })
        })

        $('#language-example').html(module.tt('example_1'))
    })()
</script>
