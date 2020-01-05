<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Загрузка изображения категории</h4>
</div>
<div class="modal-body">
    <div class="form-group">
        <h2 class="text-center">Текущее изображение</h2>
        <div class="current-category-img">
            <img class="img-thumbnail" src="<?=($category['img_src'] ? '/public/i/categories/original/' . $category['id'] . '_' . $category['img_src'] : '/public/img/thumb/nopic.jpg');?>">
        </div>
    </div>
</div>
<div class="modal-footer">
    <label>Выбор файла</label>
    <input type="file" name="category_img_name">
    <input type="hidden" name="categoryId" value="<?=$category['id'];?>">
    <button onclick="loadCategoryImg(<?=$category['id'];?>);" class="btn btn-default">Загрузить</button>
</div>