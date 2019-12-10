<?php
defined('_JEXEC') or die; ?>
<table>
    <tbody>
    <tr>
        <td>Вход на базу</td>
        <td><?php if(isset($prices['dolphin_adult'])) echo $prices['dolphin_adult']['price']; ?> <span class="nodeLabelBox repTarget "><span class="nodeText editable "><span class="  ">₽</span></span></span></td>
    </tr>
    <tr>
        <td>Индивидуальное общение с дельфином, 10 мин</td>
        <td><?php if(isset($prices['dolphin_communication'])) echo $prices['dolphin_communication']['price']; ?> <span class="nodeLabelBox repTarget "><span class="nodeText editable "><span class="  ">₽</span></span></span></td>
    </tr>
    </tbody>
</table>