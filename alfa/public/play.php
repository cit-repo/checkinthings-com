<?php
    $productId = rand(1,1323);
    $option = $_GET['option'];

    if ($option == 'a') $option = 'b';
    else if ($option == 'b') $option = 'a';

?>
<table width="300" background="#dddddd">
    <tr valign="top">
        <td align="center" height="250"><img border="0" src="/image.php?product_id=<?=$productId?>&percent=0.5"></td>
    </tr>
    <tr valign="top">
        <td align="center" height="50">
            <a class="btn btn-play" onclick="playTrack('sexyit_<?=$productId?>', '<?=$option?>')">Sexy it</a>
        </td>
    </tr>
</table>