
function showHideButton() {
    if (linii.length == 0) {
        $('#btn-comanda').hide();
    } else
        $('#btn-comanda').show();
}

function incarcareProduse(y, abc, comandaMinima, pretLivrare) {
    const linie = {id: y.id, cantitate: 1, denumire: y.nume, pret: y.pret_curent, json: abc};
    let existent = false;
    linii = linii.map((l) => {
        if (l.id === linie.id) {
            existent = true;
            return {...l, cantitate: l.cantitate + 1, pret: parseFloat(y.pret_curent * (l.cantitate + 1)).toFixed(2)};
        }
        return l;
    });
    if (!existent) {
        linii.push(linie);
    }
    const x = linii.reduce((a, b) => (a + parseFloat(b.pret)), 0.00);
    const xxx = verificareTotal(x, comandaMinima, pretLivrare);
    console.log('x=', x);
    if (x > 0) {
        $('#sum').show();
        $('.cart-sum-price').text(xxx.toFixed(2) + ' Lei');
        $('.cart-sum-price-sub').text(x.toFixed(2) + ' Lei');
        $('#btn-comanda').removeClass('disabled btn-default');
        $('#btn-comanda').addClass('btn-danger');
    }
    $('#cos-list').html(linii.map(Item));
}

function incarcaSesiune(sesiuneUrl, comandaMinima, pretLivrare) {

    const loadingOverlay = $('#loadingOverlay');

    // window.onload = function () {
    $.ajax({// create an AJAX call... // get the form data
        type: 'GET', // GET or POST
        url: sesiuneUrl, // the file to call
        success: function (data) { // on success..

            json = JSON.parse(data);
            console.log("aici");
            if (json.success != false) {
                json.forEach(function (element) {
                    for (let i = 0; i < element.cantitate; i++) {
                        json1 = JSON.stringify(element.date_produs);
                        incarcareProduse(element.date_produs, json1, comandaMinima, pretLivrare);

                    }
                });
            }
            showHideButton();
            loadingOverlay.hide();
            $('.test').show();
        },
        complete: function (jqXHR, textStatus) {

        }

    });

    // };
}

//   $('.btn-app').on('click',function(){
//        const tabId=$(this).attr('href');
//        const categorieMare=$(this).attr('data-id');
//        console.log(categorieMare);
//       $.ajax({// create an AJAX call...
//                data: {'categorieMare':categorieMare}, // get the form data
//                type: 'GET', // GET or POST
//                url: '$urlProduse', // the file to call
//                success: function (data) { // on success..
//                    $(tabId+' > .box-body').html(data);
//                    //$.pjax.reload({container: '#lista_produse'});
//                }
//        });
//   });
function loadContinutSubCategorii(urlProduse) {
    $('#subcategorii_content').on('click', '.taba', function () {
        let loadingOverlay1 = $('#loadingOverlay1');
        const tabId = $(this).attr('href');
        loadingOverlay1.show();
        //  console.log('salut');
        const categorie = $(this).attr('data-id');
        $.ajax({// create an AJAX call...
            data: {'categorie': categorie}, // get the form data
            type: 'GET', // GET or POST
            url: urlProduse, // the file to call
            success: function (data) { // on success..
                $(tabId + ' > .box-body').html(data);
                //$.pjax.reload({container: '#lista_produse'});
                loadingOverlay1.hide();

            }
        });
    });
}

function verificareTotal(x, comandaMinima, pretLivrare) {
    let livrare = 0;
    if (x >= comandaMinima) {
        livrare = 0;

    } else {
        livrare = pretLivrare;
    }
    $('.cart-sum-price-livrare').text(livrare.toFixed(2) + ' Lei');
    return x + livrare;
}

function cartDeleteButton(authKey, produsSesiune, idUser, comandaMinima, pretLivrare) {
    $('.cos').on('click', '.cart-delete-button', function () {
        const id = $(this).parent().attr('data-id');
        const selector = `div[data-key='\${id}']`;
        let index = linii.findIndex(x => x.id == id);
        const l = linii[index];
        const product = JSON.parse(l.json);
        //   console.log(product);
        const bearerToken = authKey;
        $.ajax({
            type: "POST",
            url: produsSesiune,
            data: {
                id: idUser,
                produs: product.id,
                cantitate: -l.cantitate
            },
            beforeSend: function (xhr) {
                // Set the Authorization header with the Bearer token
                xhr.setRequestHeader('Authorization', 'Bearer ' + bearerToken);
            },
            success: function (data) {
                console.log(data);
            },
            error: function (error) {
                console.log(error);
            }
        });
        $(this).parent().parent().remove();
        //console.log($(this).parent().parent());
        linii.splice(index, 1);
        if (linii.length > 0) {
            const x = linii.reduce((a, b) => (a + parseFloat(b.pret)), 0.00);
            const xxx = verificareTotal(x, comandaMinima, pretLivrare);
            if (x > 0) {
                $('#sum').show();
                $('.cart-sum-price-sub').text((xxx).toFixed(2) + ' Lei');
                $('.cart-sum-price').text(x.toFixed(2) + ' Lei');
            }
            $('#cos-list').html(linii.map(Item));

        } else {
            $('#sum').hide();
            $('#btn-comanda').removeClass('btn-danger');
            $('#btn-comanda').addClass('btn-default disabled');
            $('#cos-list').html('<center><i class="fas fa-shopping-basket" style="color: #FF0000;font-size:150px;"></i></center>');
        }
        showHideButton();
    });
}

function cartRemoveButton(authKey, produsSesiune, idUser, comandaMinima, pretLivrare) {
    $('.cos').on('click', '.remove', function () {
        const id = $(this).parent().parent().attr('data-id');
        const selector = `div[data-key='\${id}']`;
        let index = linii.findIndex(x => x.id == id);
        const l = linii[index];
        const product = JSON.parse(l.json);
        linii = linii.map((l) => {
            if (l.id === product.id) {
                const bearerToken = authKey;
                $.ajax({
                    type: "POST",
                    url: produsSesiune,
                    data: {
                        id: idUser,
                        produs: product.id,
                        cantitate: -1
                    },
                    beforeSend: function (xhr) {
                        // Set the Authorization header with the Bearer token
                        xhr.setRequestHeader('Authorization', 'Bearer ' + bearerToken);
                    },
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
                return {...l, cantitate: l.cantitate - 1, pret: parseFloat(product.pret_curent * (l.cantitate - 1)).toFixed(2)};
            }
            return l;
        }).filter(item => {
            if (item.cantitate <= 0) {
                return false;
            } else {
                return true;
            }
        });
        ;
        if (linii.length > 0) {
            $('#cos-list').html(linii.map(Item));
            const x = linii.reduce((a, b) => (a + parseFloat(b.pret)), 0.00);
            const xxx = verificareTotal(x, comandaMinima, pretLivrare);
            if (x > 0) {
                $('#sum').show();
                $('.cart-sum-price').text(xxx.toFixed(2) + ' Lei');
                $('.cart-sum-price-sub').text(x.toFixed(2) + ' Lei');
                $('#btn-comanda').removeClass('disabled btn-default');
                $('#btn-comanda').addClass('btn-danger');
            }
        } else {
            $('#sum').hide();
            $('#cos-list').html('<center><i class="fas fa-shopping-basket" style="color: #FF0000;font-size:150px;"></i></center>');
            $('#btn-comanda').removeClass('btn-danger');
            $('#btn-comanda').addClass('btn-default disabled');
        }
        showHideButton();
    });
}

function cartAddButton(authKey, produsSesiune, idUser, comandaMinima, pretLivrare) {
    $('.cos').on('click', '.add', function () {
        const id = $(this).parent().parent().attr('data-id');
        const selector = `div[data-key='\${id}']`;
        let index = linii.findIndex(x => x.id == id);
        const l = linii[index];
        const product = JSON.parse(l.json);
        linii = linii.map((l) => {

//            const bearerToken = '$authKey';
//            $.ajax({
//                    type: "GET",
//                    url: '$verificaStoc?id='+product.id,
//                    beforeSend: function(xhr) {
//                    // Set the Authorization header with the Bearer token
//                        xhr.setRequestHeader('Authorization', 'Bearer ' + bearerToken);
//                    },
//                    success: function (data) {
//                        console.log(data);
//                    },
//                    error: function (error) {
//                        console.log(error);
//                    }
//                });
            if (l.id === product.id) {
                const bearerToken = authKey;
                $.ajax({
                    type: "POST",
                    url: produsSesiune,
                    data: {
                        id: idUser,
                        produs: product.id,
                        cantitate: 1
                    },
                    beforeSend: function (xhr) {
                        // Set the Authorization header with the Bearer token
                        xhr.setRequestHeader('Authorization', 'Bearer ' + bearerToken);
                    },
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
                return {...l, cantitate: l.cantitate + 1, pret: parseFloat(product.pret_curent * (l.cantitate + 1)).toFixed(2)};
            }
            return l;
        });
        console.log(linii);
        $('#cos-list').html(linii.map(Item));
        const x = linii.reduce((a, b) => (a + parseFloat(b.pret)), 0.00);
        const xxx = verificareTotal(x, comandaMinima, pretLivrare);
        if (x > 0) {
            $('#sum').show();
            $('#btn-comanda').removeClass('disabled btn-default');
            $('#btn-comanda').addClass('btn-danger');
            $('.cart-sum-price').text(xxx.toFixed(2) + ' Lei');
            $('.cart-sum-price-sub').text(x.toFixed(2) + ' Lei');
        }
        showHideButton();
    });
}

function subcategoriiAddButton(authKey, produsSesiune, idUser, comandaMinima, pretLivrare) {
    $('#subcategorii_content').on('click', '.left-corner', function () {
        let product = JSON.parse($(this).parent().parent().find('.meal-json').text());
        const json1 = $(this).parent().parent().find('.meal-json').text();
        const linie = {id: product.id, cantitate: 1, denumire: product.nume, pret: product.pret_curent, json: json1};
        let existent = false;
        const bearerToken = authKey;
        $.ajax({
            type: "POST",
            url: produsSesiune,
            data: {
                id: idUser,
                produs: product.id,
                cantitate: 1
            },
            beforeSend: function (xhr) {
                // Set the Authorization header with the Bearer token
                xhr.setRequestHeader('Authorization', 'Bearer ' + bearerToken);
            },
            success: function (data) {
                console.log(data);

            },
            error: function (error) {
                console.log(error);
            }

        });
        linii = linii.map((l) => {
            if (l.id === linie.id) {
                existent = true;
                return {...l, cantitate: l.cantitate + 1, pret: parseFloat(product.pret_curent * (l.cantitate + 1)).toFixed(2)};
            }
            return l;
        });
        if (!existent) {
            linii.push(linie);
        }
        const x = linii.reduce((a, b) => (a + parseFloat(b.pret)), 0.00);
        const xxx = verificareTotal(x, comandaMinima, pretLivrare);
        console.log('x=', x);
        if (x > 0) {
            $('#sum').show();
            $('.cart-sum-price').text(xxx.toFixed(2) + ' Lei');
            $('.cart-sum-price-sub').text(x.toFixed(2) + ' Lei');
            $('#btn-comanda').removeClass('disabled btn-default');
            $('#btn-comanda').addClass('btn-danger');
        }
        $('#cos-list').html(linii.map(Item));
        showHideButton();
    });
}
function searchBar(searchId) {
    $(searchId).on('input', function () {
        console.log($(this).serialize());
        // console.log('am apasat');
        if ($(this).val().length >= 2) {

            // timer=setTimeout(function(){
            $.ajax({// create an AJAX call...
                data: $(this).serialize(), // get the form data
                type: $(this).attr('method'), // GET or POST
                url: $(this).attr('action'), // the file to call
                success: function (data) { // on success..
                    document.getElementById("rezultate-cautare1").style.display = "block";
                    $("a[class*='taba'][class*='active']").removeClass('active');
                    $("div[class*='tab-pane'][class*='active']").removeClass('active');
                    $('#rezultate-cautare').addClass('active');
                    $("a[data-id*='-1']").addClass('active');
                    //$('.taba').first().click();
                    $(".tab-pane.active .box-body").html(data);
                    //  $.pjax.reload({container: '#lista_produse'});
                }
            });
            //      },timeout);

        } else {
            $('#rezultate-cautare').removeClass('active');
            $("a[data-id*='-1']").removeClass('active');
            $('#rezultate-cautare1').removeClass('active');
            document.getElementById("rezultate-cautare1").style.display = "none";
            $('.taba').eq(1).click();
        }
    });
}

$('#btn-comanda').on('click', function () {
    $("#confirmation-modal").modal("show");
    const items = [];
    var fd = new FormData();
    let x = 0;
    $('.cart-row').each(function () {
        if ($(this).attr('data-id')) {
            const produsKey = `LinieComanda[\${x}][produs]`;
            fd.append(produsKey, $(this).attr('data-id'));
            const cantitateKey = `LinieComanda[\${x}][cantitate]`;
            fd.append(cantitateKey, $(this).attr('data-cantitate'));
            const pretKey = `LinieComanda[\${x}][pret]`;
            fd.append(pretKey, $(this).attr('data-pret'));

            //items.push({'':$(this).attr('data-id'),'LinieComanda[\${x}][cantitate]':$(this).attr('data-cantitate'),'LinieComanda[\${x}][pret]':$(this).attr('data-pret')});
            x++;
        }
    });
});

function buttonConfirm(urlCreazaComanda) {
    $('#btn-confirma').on('click', function () {
        var textAdresa = $('#text-area-adresa').val();
        var textMentiuni = $('#text-area-mentiuni').val();
        var telefon = $("#text-nr-telefon").val();
        $.ajax({// create an AJAX call...
            data: {'mentiuni': textMentiuni, 'adresa': textAdresa, 'telefon': telefon}, // get the form data
            type: 'POST', // GET or POST
            //  contentType: false,
            //processData: false,
            url: urlCreazaComanda, // the file to call
            success: function (data) { // on success..
//                    $(tabId+' > .box-body').html(data);
                console.log('a mers');
            }
        });
    });
}

function slickSlide(urlSchimba) {
    $('.slick-slide').on('click', function () {
        let id = $(this).attr('data-id');
        $.ajax({// create an AJAX call...
            data: {'idCategorie': id}, // get the form data
            type: 'GET', // GET or POST
            url: urlSchimba, // the file to call
            success: function (data) { // on success.
                console.log(data);
                $('#subcategorii_content').html(data);
                $('.taba').eq(1).click();

            }
        });
    });
}

function socketIo(urlComenzi) {
    socket.on('previous-orders', (data) => {
        console.log('Previous Orders:', data);
        $("#text-nr-telefon").val(data);
        $.ajax({// create an AJAX call...
            data: {'telefon': data}, // get the form data
            type: 'GET', // GET or POST
            url: urlComenzi, // the file to call
            success: function (data) { // on success.
                console.log(data);
                $('#istoric').append(data);
            }
        });

    });
}