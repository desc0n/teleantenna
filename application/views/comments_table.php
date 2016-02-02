<table class="comments-block">
	<tbody>
		<tr>
			<td colspan="2">
				<div class="regform">
					<div class="form-top">
						<h1>Оставить отзыв</h1>
					</div>
					<div class="form">
						<input type="text" id="comments_name" placeholder="Имя" value=""/> *
						<input type="email" id="comments_email" placeholder="E-mail"  value="" /> *
						<input type="text" id="comments_city" placeholder="Город"  value="" /> *
						<textarea id="comments_text"></textarea> &nbsp;&nbsp;
						<input type="button" onclick="javascript: set_comments();" value="ОТПРАВИТЬ"/>
					</div>
				</div>
			</td>
		</tr>
		<?
		foreach ($comments_arr as $data) {
		?>
		<tr>
			<td class="ci" style="width:100px">
				<img src="/public/img/comment.png" alt="" style="width:60px" >
			</td>
			<td>
				<span><?=date("d.m.y", strtotime($data['date']));?>г. <?=$data['name'];?>/<?=$data['city'];?></span>
				<span class="comment-item">
				<br>
				<?=$data['text'];?>
			</td>
		</tr>
		<tr>
			<td style="height:50px">
			</td>
		</tr>
		<?
		}
		?>
	</tbody>
</table>
<script>
function set_comments () {
	if ($("#comments_name").val()=="") {
		alert("Не указано имя!");
	}
	else if ($("#comments_email").val()=="") {
		alert("Не указана электронная почта!");
	}
	else if ($("#comments_city").val()=="") {
		alert("Не указан город!");
	}
	else if ($("#comments_text").val()=="") {
		alert("Не введен текст комментария!");
	}
	else {
		var html = $.ajax({
			type: 'POST',
			url: '/ajax/setcomtab',
			async: true,
			data: {
				name: $("#comments_name").val(),
				email: $("#comments_email").val(),
				city: $("#comments_city").val(),
				text: $("#comments_text").val()
			}
		}).done(
			function(html) {
				get_comments_table ();
			}
		);
	}
}
</script>