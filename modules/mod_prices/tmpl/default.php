<?php
defined('_JEXEC') or die; ?>
    <table style="width: 327.383px;">
        <tbody>
        <tr style="height: 31px;">
            <td style="height: 31px; width: 233px;">
                <div class="caption">Взрослые (12+) /</div>
                <div class="caption">Взрослые VIP места*</div>
            </td>
            <td style="height: 31px; width: 67.3833px;">
                <div class="caption"><?php if(isset($prices['show_adult'])) echo $prices['show_adult']['price']; ?> <span class="nodeLabelBox repTarget "><span class="nodeText editable "><span
                                    class="  ">₽ /</span></span></span></div>
                <div class="caption"><span class="nodeLabelBox repTarget "><span class="nodeText editable "><span
                                    class="  "><?php if(isset($prices['show_adult_vip'])) echo $prices['show_adult_vip']['price']; ?> <span class="nodeLabelBox repTarget "><span
                                            class="nodeText editable "><span
                                                class="  ">₽</span></span></span></span></span></span></div>
            </td>
        </tr>
        <tr style="height: 15px;">
            <td style="height: 15px; width: 233px;">
                <div class="caption" style="text-align: left;">Детские (5-11) /</div>
                <div class="caption" style="text-align: left;">Детские VIP места*</div>
            </td>
            <td style="height: 15px; width: 67.3833px;">
                <div class="caption"><?php if(isset($prices['show_child'])) echo $prices['show_child']['price']; ?> <span class="nodeLabelBox repTarget "><span class="nodeText editable "><span
                                    class="  ">₽ </span></span></span><span class="nodeLabelBox repTarget "><span
                                class="nodeText editable "><span class="  "><span class="nodeLabelBox repTarget "><span
                                            class="nodeText editable "><span
                                                class="  ">/ </span></span></span></span></span></span></div>
                <div class="caption"><span class="nodeLabelBox repTarget "><span class="nodeText editable "><span
                                    class="  "><span class="nodeLabelBox repTarget "><span
                                            class="nodeText editable "><span class="  "><?php if(isset($prices['show_child_vip'])) echo $prices['show_child_vip']['price']; ?> <span
                                                    class="nodeLabelBox repTarget "><span
                                                        class="nodeText editable "><span
                                                            class="  ">₽</span></span></span></span></span></span></span></span></span>
                </div>
            </td>
        </tr>
        <tr style="height: 5.08333px;">
            <td style="height: 5.08333px; width: 233px;">Дети (0-4)
                <p>без предоставления отдельного места, в сопровождении взрослого.</p>
            </td>
            <td style="height: 5.08333px; width: 67.3833px;">Бесплатно</td>
        </tr>
        <tr style="height: 53px;">
            <td style="height: 53px; width: 233px;">Льготники
                <p>(Многодетные семьи и инвалиды 1-2 группы)</p>
                <p>* На VIP места скидки и льготы не распространяются</p>
            </td>
            <td style="height: 53px; width: 67.3833px;">50 %</td>
        </tr>
        <tr style="height: 15px;">
            <td style="height: 15px; width: 233px;">Участники ВОВ</td>
            <td style="height: 15px; width: 67.3833px;">Бесплатно</td>
        </tr>
        </tbody>
    </table>