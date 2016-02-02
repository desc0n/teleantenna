<div class="row">
	<div class="col-sm-12">
		<ol class="breadcrumb">
			<li><a href="/admin">Операции с товаром</a></li>
			<li><a href="/admin/?action=incomes">Список поступлений</a></li>
			<li class="active">Поступление</li>
		</ol>
	</div>
	<h2 class="sub-header col-sm-12">Поступление № <?=$income_id;?></h2>
	<div class="col-sm-11">
		<?if($document_status == 1){?>
		<form action='/admin/income/?income=<?=$income_id;?>' method='post'>
		<?}?>
            <h3 class="col-sm-3">Ответственный</h3>
            <h3 class="col-sm-7">
                <? if ($document_status == 1) {?>
                    <select class="form-control" id="incomeContractor" name="incomeContractor">
                        <option value="" data-discount="0" <?=($contractor_id == 0 ? 'selected' : '');?>>Не указан</option>
                        <?foreach ($contractorList as $contractorData) {?>
                            <option value="<?=$contractorData['user_id'];?>" data-discount="<?=$contractorData['discount'];?>" <?=($contractor_id == $contractorData['user_id'] ? 'selected' : '');?>><?=(!empty($contractorData['name']) ? $contractorData['name'] : $contractorData['username']);?></option>
                        <?}?>
                    </select>
                    <input type="hidden" id="discount" value="0">
                <?} else {
                    if ($contractor_id == 0) {?>
                        Не указан
                        <?
                    } else {
                        foreach ($contractorList as $contractorData) {
                            if ($contractor_id == $contractorData['user_id']) { ?>
                                <?= (!empty($contractorData['name']) ? $contractorData['name'] : $contractorData['username']); ?>
                                <?
                            }
                        }
                    }?>
                <?}?>
            </h3>
            <div>
                <table class="table table-hover table-bordered table-striped income-table">
                    <thead>
                        <tr>
                            <th class="col-sm-1 col-code">Код</th>
                            <th>Наименование</th>
                            <th class="col-sm-3">Комментарий</th>
                            <th class="col-sm-1 text-center col-num" colspan="">Количество (шт.)</th>
                            <th class="col-sm-1 text-center col-num" colspan="">Наличие (шт.)</th>
                            <th class="col-sm-1 text-center col-num" colspan="">Действия</th>
                        </tr>
                    </thead>
                    <tbody id='tableBody'>
                    <?if($document_status == 1){
                        $i = 1;
                        foreach ($incomeData as $data){
                            ?>
                            <tr>
                                <td>
                                    <?=$data['product_code'];?>
                                    <input type='hidden' id='goodsId_<?=$i;?>' row='<?=$i;?>' name='id[]' value='<?=$data['product_id'];?>'>
                                </td>
                                <td class='text-left'>
                                    <?=$data['product_name'];?>
                                </td>
                                <td class='text-left'>
                                    <?=$data['comment'];?>
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
                            $i++;
                        }
                        ?>
                        <tr>
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
                                <input type=text class='form-control' id='goodsComment_<?=$i;?>' row='<?=$i;?>' name='comment[]' value=''>
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
                        foreach ($incomeData as $data){
                            ?>
                            <tr>
                                <td>
                                    <?=$data['product_code'];?>
                                </td>
                                <td class='text-left'>
                                    <?=$data['product_name'];?>
                                </td>
                                <td class='text-left'>
                                    <?=$data['comment'];?>
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
                            $i++;
                        }
                    }
                    ?>
                    </tbody>
                    <?if($document_status == 1){?>
                    <tr>
                        <td colspan="6" class="text-left">
                            <button type="button" class='btn btn-primary' id='addRowBtn' onclick="addCommentRow();">Добавить строку</button>
                        </td>
                    </tr>
                    <?}?>
                </table>
            </div>
		<?if($document_status == 1){?>
			<button class='btn btn-success' name='carryOutIncomePost' value='<?=$income_id;?>'>Провести</button>
		</form>
		<?}?>
	</div>
	<input type='hidden' id='incomeId' value='<?=$income_id;?>'>
	<input type='hidden' id='rowNum' value='1'>
</div>