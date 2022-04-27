function toCart(id_product) {
    $.ajax({
        method: "GET",
        url: `/site/to-cart?id_product=${id_product}`,
    })
        .done(function (msg) {
            $.pjax.reload({
                container:'#cart'
            });
            alert(msg);
        });
}

function addCart(id_product) {
    $.ajax({
        method: "GET",
        url: `/site/to-cart?id_product=${id_product}`,
    })
        .done(function (msg) {
            $.pjax.reload({
                container:'#cart'
            });
        });
}

function removeCart(id_product) {
    $.ajax({
        method: "GET",
        url: `/site/remove-cart?id_product=${id_product}`,
    })
        .done(function (msg) {
            $.pjax.reload({
                container:'#cart'
            });
        });
}

function byOrder(){
    const password = document.querySelector('.password');
    if (!password.value) {
        alert('Пустой нельзя');
        return;
    }

    $.ajax({
        method: "GET",
        url: `/site/by-order?password=${password.value}`,
    })
        .done(function (msg) {
            alert(msg);
            $.pjax.reload({
                container:'#cart'
            });
        });
}