function poClose( ) {
	var modal = document.querySelector('#windowClick');
	if (!modal) {
		return;
	}
	if (!modal.hasAttribute('data-show')) {
		return;
	}
	modal.removeAttribute('data-show');
}

function basket() {
	var modal = document.querySelector('#windowClick');
	if (!modal) {
		return;
	}
	if (modal.hasAttribute('data-show')) {
		return;
	}
	modal.setAttribute('data-show', 'show');
}

var nameForm = '';
var emailForm = '';
var telForm = '';
function proverkaFormi () {
	var reEmail = /^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/;
	var rePhone  = /(^(\+7|7|8)([0-9]){10}$)|(^([0-9]){7}$)/;
	var reName = /^[а-яА-ЯёЁ'][а-яА-ЯёЁ']+[а-яА-ЯёЁ']?$/;

	var temp = '';

	if ((nameForm === '') && (emailForm === '') && (telForm === ''))
    {alert('Заполните форму!'); return 0;}
	else {
		if (nameForm.length< 3 )
            {
                alert('Заполните поле "Ваше имя"');
                return false;
            }
		if (telForm !== '')
         {
        	if (!(rePhone.test(telForm)))
            {
                alert('Проверьте ввод номера телефона!');
                return 0;
            }
         }
		else {alert('Введите номер телефона!'); return 0;}

		if (emailForm !== '')
        {
			if (!(reEmail.test(emailForm)) || (emailForm === ''))
            {
               alert("Введите правильный E-Mail ");
                return false;
            }
        }
			else { alert("Введите E-Mail "); return 0;}

	}

}

function handleAddKorzinaSubmit(event, id) {
	var countSrc = document.querySelector(`[data-relate="item-${id}"]`);
	var countDes = event.currentTarget.querySelector(`[name="count"]`);
	countDes.value = countSrc.value;

	if (Number(countSrc.value) === 0) {
		alert('Нужно указать количество');
		event.preventDefault();
	}
}

function handleClearSubmit(event) {

}

function handleZakazSubmit(event) {
	if (proverkaFormi() == 0) {
		event.preventDefault();
		return false;
	}
}
