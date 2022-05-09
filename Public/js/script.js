function registerUser(datos)
{
    $.ajax({
        url: '/talent/index.php',
        type: 'POST',
        data: datos,
        dataType: 'json',
        beforeSend: function(){
            $('#btnRegister').prop('disabled', true).html('Wait...');
        },
        success: function(jsonInfo){
            if(jsonInfo.response === true){
                $('#btnRegister').remove();
                $(".statusRegister").addClass('text-success text-center').removeClass('text-danger text-left').html('User register!');

                setTimeout(function(){
                    $(".statusRegister").html('');
                    $("#formToLogin").submit();
                }, 3000);
            }
            else{
                $(".statusRegister").removeClass('text-success text-center').addClass('text-danger text-left').html('<ul><li>'+jsonInfo.response.join('</li><li>')+'</li></ul>');
                $('#btnRegister').prop('disabled', false).html('Submit');

                setTimeout(function(){
                    $(".statusRegister").html('');
                }, 3000);
            }
        },
        error: function(a, b, c, d){
            console.log(a, b, c, d);
        }
    });
}

function loginUser(datos)
{
    $.ajax({
        url: '/talent/index.php',
        type: 'POST',
        data: datos,
        dataType: 'json',
        beforeSend: function(){
            $('#btnLogin').prop('disabled', true).html('Wait...');
        },
        success: function(jsonInfo){
            if(jsonInfo.response == 'ok'){
                location.href = '/talent/index.php';
            }
            else{
                $(".statusRegister").html(jsonInfo.response);
                $('#btnLogin').prop('disabled', false).html('Submit');

                setTimeout(function(){
                    $(".statusRegister").html('');
                }, 3000);
            }
        },
        error: function(a, b, c, d){
            console.log(a, b, c, d);
        }
    });
}

function updateMovieList()
{
    var thisme = this;
    $.ajax({
        url: '/talent/index.php',
        type: 'POST',
        data: {action: 'updateMovieList'},
        dataType: 'json',
        beforeSend: function(){
            $(thisme).prop('disabled', true).html('Updating...');
        },
        success: function(jsonInfo){
            $(thisme).prop('disabled', false).html('Update Movie List');
            $("#formSearchMovies")[0].reset();

            viewTableMovies(jsonInfo.response.Search);
        },
        error: function(a, b, c, d){
            console.log(a, b, c, d);
        }
    });
}

function searchMovieList()
{
    $.ajax({
        url: '/talent/index.php',
        type: 'POST',
        data: $("#formSearchMovies").serialize(),
        dataType: 'json',
        beforeSend: function(){
            $("#formSearchMovies").find('#btnSearchMovie').prop('disabled', true).html('Searching...');
        },
        success: function(jsonInfo){
            $("#formSearchMovies").find('#btnSearchMovie').prop('disabled', false).html('Search');
            viewTableMovies(jsonInfo.response.Search);
        },
        error: function(a, b, c, d){
            console.log(a, b, c, d);
        }
    });
}

function viewTableMovies(infoMovies)
{
    // Tabla Principal
    var table = $("#tableMovies tbody");

    // Elimino los Registros
    table.find('.cloned').remove();

    // Recorro los Nuevos Registros
    $.each(infoMovies, function(key, val){

        // Clono el Tr
        let clone = table.find('.cloneme').clone();
        $(clone).removeClass('d-none cloneme').addClass('cloned');

        // Actualizo los datos del TD
        $(clone).find('td:nth-child(1)').html(val.Title);
        $(clone).find('td:nth-child(2)').html(val.Year);
        $(clone).find('td:nth-child(3)').html(val.imdbID);
        $(clone).find('td:nth-child(4)').html(val.Type);
        $(clone).find('td:nth-child(5)').html(`<img src="${val.Poster}" title="${val.Title}" alt="${val.Title}" style="height: 300px; width: auto;">`);

        table.append(clone);
    });
}

$(document).ready(function(){
    $(".toRegister").click(function(){
        $("#formToRegister").submit();
    });

    $(".toLogin").click(function(){
        $("#formToLogin").submit();
    });

    $(".btnUpdateList").on("click", updateMovieList);
    $(".btnUpdateList").on("click", );

    // Evento de Logueo
    $("#formSearchMovies").on("submit", function(e){
        e.preventDefault();
        return searchMovieList();
    });

    // Evento de Logueo
    $("#formLogin").on("submit", function(e){
        e.preventDefault();
        var datos = $(this).serialize();
        return loginUser(datos);
    });

    // Evento de Registro
    $("#formCreateAccount").on("submit", function(e){
        e.preventDefault();
        var datos = $(this).serialize();
        return registerUser(datos);
    });
});