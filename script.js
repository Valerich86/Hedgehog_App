
function validate(form){
    fail = validateForename(form.forename.value)
    fail += validateSurname(form.surname.value)
    fail += validateUsername(form.username.value)
    fail += validatePassword(form.password.value)
    fail += validateAge(form.age.value)
    fail += validateEmail(form.email.value)

    if (fail == '') return true
    else {
        i = document.getElementById('error2');
        p = document.getElementById('error1');
        i.textContent = fail
        i.style.color = 'red'
        i.style.fontSize = '10px'
        p.textContent = "В вашей форме найдены ошибки: "
        p.style.color = 'red'
        return false
    }
}

function validateForename(field){
    return (field == "") ? "Не введено имя.\n" : ""
}

function validateSurname(field){
    return (field == "") ? "Не введена фамилия.\n" : ""
}

function validateUsername(field){
    if (field == "") return "Не введено имя пользователя.\n"
    else if (field.length<5)
        return "В имени пользователя должно быть не менее 5 символов.\n"
    else if (/[^a-zA-Z0-9_-]/.test(field))
        return "В имени пользователя имеются недопустимые символы.\n"
    return ""
}

function validatePassword(field){
    if (field == "") return "Не введен пароль.\n"
    else if (field.length<6)
        return "В пароле должно быть не менее 6 символов.\n"
    else if (!/[a-z]/.test(field) || !/[A-Z]/.test(field) || !/[0-9]/.test(field))
        return "В пароле должны быть использованы буквы латиницы и цифры.\n"
    return ""
}

function validateAge(field){
    if (field == "" || isNaN(field)) return "Не введен возраст.\n"
    else if (field < 18)
        return "Возраст должен быть больше или равен 18.\n"
    return ""
}

function validateEmail(field){
    if (field == "") return "Не введен адрес электронной почты.\n"
    else if (!((field.indexOf(".") > 0) && (field.indexOf("@") > 0)) || /[^a-zA-Z0-9.@_-]/.test(field))
        return "Электронный адрес имеет неверный формат.\n"
    return ""
}