<script>
/**
TODO
    срок
        Просрочен красный
        Сегодня/завтра желтый

*/
$(function() {
	$("td:contains(Эпик)").css("color","blue");
	$("td:contains(Ошибка)").css("color","red");
	$("td:contains(Решен)").css("color","green");
	$("td:contains(Закрыт)").css("color","green");
	$("td:contains(В работе)").css("color","yellow");
	$("td:contains(Блокер)").css("color","red");
	$("td:contains(Высокий)").css("color","red");
	$("td:contains(Средний)").css("color","yellow");
});
</script>