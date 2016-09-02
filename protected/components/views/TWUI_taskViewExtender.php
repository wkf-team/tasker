<script>
/**
TODO
    срок
        Просрочен красный
        Сегодня/завтра желтый

*/
$(function() {
	initColors();
});

function initColors() {
	$(".filters select").change(function () { setTimeout(initColors, 2000); });
	$(".yiiPager a").click(function () { setTimeout(initColors, 2000); });
	setColors();
}

function setColors() {
	$("td:contains(Эпик)").css("color","blue");
	$("td:contains(Ошибка)").css("color","red");
	$("td:contains(Решен)").css("color","green");
	$("td:contains(Закрыт)").css("color","green");
	$("td:contains(В работе)").css("color","orange");
	$("td:contains(На тестировании)").css("color","orange");
	$("td:contains(Блокирован)").css("color","red");
	$("td:contains(Переоткрыт)").css("color","red");
	
	$("td:contains(Закрыт частично)").css("color","orange");
	$("td:contains(Выполнен)").css("color","green");
	$("td:contains(Не выполнен)").css("color","red");
	$("span:contains(Закрыт частично)").css("color","orange");
	$("span:contains(Выполнен)").css("color","green");
	$("span:contains(Не выполнен)").css("color","red");
	
	$("td:contains(Блокер)").css("color","red");
	$("td:contains(Высокий)").css("color","red");
	$("td:contains(Средний)").css("color","orange");
}
</script>