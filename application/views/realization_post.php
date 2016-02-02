<div class="row">
	<div class="col-sm-12">
		<ol class="breadcrumb">
			<li><a href="/admin">Операции с товаром</a></li>
			<li><a href="/admin/?action=realizations">Список реализаций</a></li>
			<li class="active">Реализация</li>
		</ol>
	</div>
	<h2 class="sub-header col-sm-12">Реализация № <?=$realization_id;?></h2>
    <div class="col-sm-12">
    <?if($document_status == 1){?>
    <form action='/admin/realization/?realization=<?=$realization_id;?>' method='post' id='carryOutRealizationForm' target="_blank">
    <?}?>
        <h3 class="col-sm-2">Контрагент</h3>
        <h3 class="col-sm-6">
            <? if (Arr::get(Arr::get($realizationData, 0, []), 'realization_status', 1) == 1) {?>
            <select class="form-control" id="realizationContractor" name="realizationContractor">
                <option value="" data-discount="0" <?=($contractor_id == 0 ? 'selected' : '');?>>Розничный покупатель (0%)</option>
                <?foreach ($contractorList as $contractorData) {?>
                <option value="<?=$contractorData['user_id'];?>" data-discount="<?=$contractorData['discount'];?>" <?=($contractor_id == $contractorData['user_id'] ? 'selected' : '');?>><?=(!empty($contractorData['name']) ? $contractorData['name'] : $contractorData['username']);?> (<?=$contractorData['discount'];?> %)</option>
                <?}?>
            </select>
            <input type="hidden" id="discount" value="0">
            <?} else {
                if ($contractor_id == 0) {?>
                    Розничный покупатель (0%)
                <?
                } else {
                    foreach ($contractorList as $contractorData) {
                        if ($contractor_id == $contractorData['user_id']) { ?>
                            <?= (!empty($contractorData['name']) ? $contractorData['name'] : $contractorData['username']); ?> (<?= $contractorData['discount']; ?> %)
                            <?
                        }
                    }
                }?>
            <?}?>
        </h3>
        <div>
            <table class="table table-hover table-bordered table-striped realization-table">
                <thead>
                    <tr>
                        <th class="col-sm-1 col-code">Код</th>
                        <th>Наименование</th>
                        <th class="col-sm-1 text-center col-price">Цена</th>
                        <th class="col-sm-1 text-center col-num" colspan="">Кол-во (шт.)</th>
                        <th class="col-sm-1 text-center col-num" colspan="">Наличие (шт.)</th>
                        <th class="col-sm-1 text-center col-num" colspan="">Действия</th>
                    </tr>
                </thead>
                <tbody id='tableBody'>
                <?if($document_status == 1){?>
                    <?
                    $i = 1;
                    foreach ($realizationData as $data){
                    ?>
                    <tr>
                        <td>
                            <?=$data['product_code'];?>
                            <input type='hidden' id='goodsId_<?=$i;?>' row='<?=$i;?>' name='id[]' value='<?=$data['product_id'];?>'>
                        </td>
                        <td class='text-left'>
                            <?=$data['product_name'];?>
                        </td>
                        <td>
                            <?=$data['price'];?>
                            <input type=hidden id='goodsPrice_<?=$i;?>' row='<?=$i;?>' name='price[]' value='<?=$data['price'];?>'>
                        </td>
                        <td>
                            <?=$data['num'];?>
                            <input type=hidden id='goodsNum_<?=$i;?>' row='<?=$i;?>' name='num[]' value='1'>
                        </td>
                        <td>
                            <?=$data['root_num'];?>
                        </td>
                        <td>
                        </td>
                    </tr>
                        <?
                        $i++;
                    }
                    ?>
                    <tr id="row<?=$i;?>">
                        <td>
                            <input type=text class='form-control' id='goodsCode_<?=$i;?>' row='<?=$i;?>' value='' autocomplete="OFF" data-items="7"  onkeyup="javascript: initTypeahead($(this).attr('row'), $(this).val(), 'code');">
                            <input type='hidden' id='goodsId_<?=$i;?>' row='<?=$i;?>' name='id[]' value=''>
                            <div class="col-xs-12 admin-typeahead" id="typeaheadCode<?=$i;?>"></div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type=text class='form-control' id='goodsName_<?=$i;?>' row='<?=$i;?>' value='' autocomplete="OFF" data-items="7" onkeyup="javascript: initTypeahead($(this).attr('row'), $(this).val(), 'name');">
                                <div class="col-xs-12 admin-typeahead" id="typeaheadName<?=$i;?>"></div>
                                <div class="input-group-btn">
                                    <button  type="button" class="btn btn-default" onclick="javascript: openSearchModal(<?=$i;?>);"><span class="glyphicon glyphicon-search"></span></button>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type=text class='form-control' id='goodsPrice_<?=$i;?>' row='<?=$i;?>' name='price[]' value=''>
                        </td>
                        <td>
                            <input type=text class='form-control' id='goodsNum_<?=$i;?>' row='<?=$i;?>' name='num[]' value='1'>
                        </td>
                        <td>
                            <span id="rootNum_<?=$i;?>"></span>
                            <input type='hidden' id='goodsRootNum_<?=$i;?>' row='<?=$i;?>' value='0'>
                        </td>
                        <td class='text-center'>
                        </td>
                    </tr>
                        <?
                        } else if($document_status == 2){
                            $i = 1;
                            foreach ($realizationData as $data){?>
                    <tr>
                        <td>
                            <?=$data['product_code'];?>
                        </td>
                        <td class='text-left'>
                            <?=$data['product_name'];?>
                        </td>
                        <td>
                            <?=$data['price'];?>
                        </td>
                        <td>
                            <?=$data['num'];?>
                        </td>
                        <td>
                            <?=$data['root_num'];?>
                        </td>
                        <td>
                        </td>
                    </tr>
                        <?
                        }
                        $i++;
                    }
                    ?>
                </tbody>
                <?if($document_status == 1){?>
                    <tr>
                        <td colspan="6" class="text-left">
                            <button type="button" class='btn btn-primary' id='addRowBtn' onclick="addRealizationRow();">Добавить строку</button>
                        </td>
                    </tr>
                <?}?>
            </table>
        </div>
    <?if($document_status == 1){?>
        <input type='hidden' name='carryOutRealizationPost' value='<?=$realization_id;?>'>
        <button type="button" class='btn btn-success' id='carryOutRealizationPost'>Провести</button>
    </form>
    <?}?>
    </div>
</div>
<input type='hidden' id='realizationId' value='<?=$realization_id;?>'>
<input type='hidden' id='rowNum' value='1'>
<?=(preg_match('/\.lan/i', $_SERVER['SERVER_NAME']) ? '' : View::factory('search_modal'));?>